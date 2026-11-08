document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('airliner-filter');
    const resultsContainer = document.getElementById('airliner-results');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const countBadge = document.querySelector('[data-catalog-count]');

    if (!filterForm || !resultsContainer) return;

    const sortSelect = document.querySelector('[data-catalog-sort]');
    const searchInput = document.querySelector('[data-catalog-search]');

    const ajaxUrl = window.wpData?.ajaxUrl || '/wp-admin/admin-ajax.php';

    const allowedGroups = ['brand', 'fuselage', 'status'];
    const pillGroups = ['brand', 'fuselage'];

    let replaceController = null;
    let appendController = null;

    let replaceRequestId = 0;
    let appendRequestId = 0;

    let isAppendLoading = false;
    let suppressResetHandler = false;

    // ==========================================
    // 1. URL И HISTORY API
    // ==========================================

    const updateURL = () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        allowedGroups.forEach(group => {
            const values = formData.getAll(`${group}[]`);

            if (values.length) {
                params.set(group, values.join(','));
            }
        });

        ['range', 'passengers'].forEach(field => {
            const value = formData.get(field);

            if (value) {
                params.set(field, value);
            }
        });

        const search = formData.get('search');

        if (typeof search === 'string' && search.trim()) {
            params.set('search', search.trim());
        }

        const sort = formData.get('sort');

        if (sort && sort !== 'default') {
            params.set('sort', sort);
        }

        const queryString = params.toString();

        const newUrl =
            window.location.pathname +
            (queryString ? `?${queryString}` : '') +
            window.location.hash;

        const currentUrl =
            window.location.pathname +
            window.location.search +
            window.location.hash;

        // Не создаём повторную запись с тем же URL.
        if (newUrl === currentUrl) return;

        window.history.pushState(
            { path: newUrl },
            '',
            newUrl
        );
    };

    const applyURLParams = () => {
        const params = new URLSearchParams(window.location.search);

        let hasFilterParams = false;

        // Восстанавливаем чекбоксы.
        allowedGroups.forEach(group => {
            const values = params.get(group);

            if (!values) return;

            hasFilterParams = true;

            const selectedValues = new Set(
                values.split(',').filter(Boolean)
            );

            const inputs = filterForm.querySelectorAll(
                `input[name="${group}[]"]`
            );

            inputs.forEach(input => {
                input.checked = selectedValues.has(input.value);
            });
        });

        // Восстанавливаем числовые поля.
        ['range', 'passengers'].forEach(field => {
            if (!params.has(field)) return;

            hasFilterParams = true;

            const input = filterForm.querySelector(
                `input[name="${field}"]`
            );

            if (input) {
                input.value = params.get(field);
            }
        });

        // Восстанавливаем поиск.
        if (params.has('search') && searchInput) {
            hasFilterParams = true;
            searchInput.value = params.get('search');
        }

        // Восстанавливаем сортировку.
        if (params.has('sort') && sortSelect) {
            const sortValue = params.get('sort');

            const optionExists = Array.from(sortSelect.options).some(
                option => option.value === sortValue
            );

            if (optionExists) {
                hasFilterParams = true;
                sortSelect.value = sortValue;
            }
        }

        return hasFilterParams;
    };

    // ==========================================
    // 2. ИНДИКАЦИЯ ЗАГРУЗКИ
    // ==========================================

    const setLoadMoreLoading = (loading) => {
        if (!loadMoreBtn) return;

        const text = loadMoreBtn.querySelector(
            '.load-more-btn__text'
        );

        const loader = loadMoreBtn.querySelector(
            '.load-more-btn__loader'
        );

        loadMoreBtn.disabled = loading;
        loadMoreBtn.setAttribute(
            'aria-busy',
            loading ? 'true' : 'false'
        );

        if (text) {
            text.style.display = loading ? 'none' : '';
        }

        if (loader) {
            loader.style.display = loading ? 'inline' : 'none';
        }
    };


    const setResultsLoading = (loading) => {
        resultsContainer.style.opacity = loading ? '0.3' : '1';
        resultsContainer.setAttribute(
            'aria-busy',
            loading ? 'true' : 'false'
        );
    };


    const updateLoadMore = (data) => {
        if (!loadMoreBtn || !loadMoreBtn.parentElement) return;

        const currentPage = Number(data.currentPage) || 1;
        const maxPages = Number(data.max_pages) || 0;

        loadMoreBtn.dataset.page = currentPage;
        loadMoreBtn.dataset.maxPages = maxPages;

        loadMoreBtn.parentElement.style.display =
            currentPage >= maxPages ? 'none' : '';
    };

    // ==========================================
    // 3. AJAX ЗАПРОСЫ
    // ==========================================

    const fetchAirliners = async (
        isAppend = false,
        { updateHistory = true } = {}
    ) => {
        // Не допускаем повторную загрузку следующей страницы.
        if (isAppend) {
            if (!loadMoreBtn || isAppendLoading || replaceController) {
                return;
            }

            const currentPage = Number(loadMoreBtn.dataset.page) || 1;
            const maxPages = Number(loadMoreBtn.dataset.maxPages) || 0;

            if (currentPage >= maxPages) return;

            isAppendLoading = true;
            setLoadMoreLoading(true);
        } else {
            // Отменяем предыдущие запросы.
            if (replaceController) {
                replaceController.abort();
            }

            if (appendController) {
                appendController.abort();
                appendController = null;

                appendRequestId++;
                isAppendLoading = false;
                setLoadMoreLoading(false);
            }

            setResultsLoading(true);

            if (updateHistory) {
                updateURL();
            }
        }

        const controller = new AbortController();

        let requestId;

        if (isAppend) {
            appendController = controller;
            requestId = ++appendRequestId;
        } else {
            replaceController = controller;
            requestId = ++replaceRequestId;
        }

        try {
            const formData = new FormData(filterForm);

            formData.append('action', 'filter_airliners');

            const currentPage = isAppend
                ? (Number(loadMoreBtn.dataset.page) || 1) + 1
                : 1;

            formData.append('page', currentPage);

            const response = await fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                signal: controller.signal
            });

            // Читаем JSON даже при HTTP-ошибке,
            // чтобы получить сообщение от WordPress.
            let result;

            try {
                result = await response.json();
            } catch {
                throw new Error('Сервер вернул некорректный ответ.');
            }

            if (!response.ok || !result.success) {
                const message =
                    result?.data?.message ||
                    `Ошибка сервера: ${response.status}`;

                throw new Error(message);
            }

            // Проверяем, что ответ всё ещё актуален.
            const isCurrentRequest = isAppend
                ? requestId === appendRequestId
                : requestId === replaceRequestId;

            if (!isCurrentRequest) return;

            if (typeof result.data?.html !== 'string') {
                throw new Error('В ответе сервера отсутствует HTML.');
            }

            // Обновляем карточки.
            if (isAppend) {
                resultsContainer.insertAdjacentHTML(
                    'beforeend',
                    result.data.html
                );
            } else {
                resultsContainer.innerHTML = result.data.html;
            }

            // Обновляем количество найденных лайнеров.
            if (countBadge && result.data.count !== undefined) {
                countBadge.textContent = result.data.count;
            }

            // Обновляем пагинацию.
            updateLoadMore(result.data);

        } catch (error) {
            if (error.name === 'AbortError') return;

            console.error('Ошибка загрузки каталога:', error);

            // Сохраняем текущие результаты при ошибке.
            // Подробности доступны в консоли разработчика.
        } finally {
            const isCurrentRequest = isAppend
                ? requestId === appendRequestId
                : requestId === replaceRequestId;

            // Старый запрос не должен менять состояние
            // более нового запроса.
            if (!isCurrentRequest) return;

            if (isAppend) {
                appendController = null;
                isAppendLoading = false;

                setLoadMoreLoading(false);
            } else {
                replaceController = null;

                setResultsLoading(false);
            }
        }
    };

    // ==========================================
    // 4. СИНХРОНИЗАЦИЯ PILLS
    // ==========================================

    const syncPillsWithForm = () => {
        pillGroups.forEach(group => {
            const checkedInputs = filterForm.querySelectorAll(
                `input[name="${group}[]"]:checked`
            );

            const checkedIds = new Set(
                Array.from(checkedInputs).map(input => input.value)
            );

            const allPill = document.querySelector(
                `[data-pill-all="${group}"]`
            );

            const itemPills = document.querySelectorAll(
                `[data-pill-group="${group}"]`
            );

            // Если ничего не выбрано — активна кнопка «Все».
            if (checkedIds.size === 0) {
                if (allPill) {
                    allPill.classList.add('is-active');
                }

                itemPills.forEach(pill => {
                    pill.classList.remove('is-active');
                });

                return;
            }

            if (allPill) {
                allPill.classList.remove('is-active');
            }

            itemPills.forEach(pill => {
                const termId = pill.dataset.termId;

                pill.classList.toggle(
                    'is-active',
                    checkedIds.has(termId)
                );
            });
        });
    };

    // ==========================================
    // 5. СОБЫТИЯ ФОРМЫ
    // ==========================================

    // Отправка фильтров.
    filterForm.addEventListener('submit', event => {
        event.preventDefault();

        fetchAirliners(false);
    });

    // Смена сортировки.
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            fetchAirliners(false);
        });
    }

    // Поиск по Enter.
    if (searchInput) {
        searchInput.addEventListener('keydown', event => {
            if (event.key !== 'Enter') return;

            event.preventDefault();

            fetchAirliners(false);
        });
    }

    // Сброс фильтров.
    filterForm.addEventListener('reset', () => {
        if (suppressResetHandler) return;

        // Браузер сбрасывает поля после события reset.
        // Поэтому выполняем запрос в следующем цикле.
        setTimeout(() => {
            syncPillsWithForm();
            fetchAirliners(false);
        }, 0);
    });

    // ==========================================
    // 6. ПОКАЗАТЬ ЕЩЁ
    // ==========================================

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            fetchAirliners(true);
        });
    }

    // ==========================================
    // 7. ИСТОРИЯ БРАУЗЕРА
    // ==========================================

    window.addEventListener('popstate', () => {
        // Отключаем AJAX-обработчик стандартного reset.
        suppressResetHandler = true;

        filterForm.reset();

        suppressResetHandler = false;

        // Восстанавливаем значения из текущего URL.
        applyURLParams();

        syncPillsWithForm();

        // Загружаем результаты без создания новой записи истории.
        fetchAirliners(false, {
            updateHistory: false
        });
    });

    // ==========================================
    // 8. БЫСТРЫЕ ТАБЛЕТКИ
    // ==========================================

    document.addEventListener('click', event => {
        // Клик по кнопке «Все бренды» или «Все типы».
        const allBtn = event.target.closest('[data-pill-all]');

        if (allBtn) {
            const group = allBtn.dataset.pillAll;

            if (!pillGroups.includes(group)) return;

            event.preventDefault();

            const inputs = filterForm.querySelectorAll(
                `input[name="${group}[]"]`
            );

            inputs.forEach(input => {
                input.checked = false;
            });

            syncPillsWithForm();
            fetchAirliners(false);

            return;
        }

        // Клик по конкретной таблетке.
        const itemBtn = event.target.closest('[data-pill-item]');

        if (itemBtn) {
            const group = itemBtn.dataset.pillGroup;
            const termId = itemBtn.dataset.termId;

            if (!pillGroups.includes(group) || !termId) return;

            event.preventDefault();

            const inputs = filterForm.querySelectorAll(
                `input[name="${group}[]"]`
            );

            const checkbox = Array.from(inputs).find(
                input => input.value === termId
            );

            if (!checkbox) return;

            checkbox.checked = !checkbox.checked;

            syncPillsWithForm();
            fetchAirliners(false);
        }
    });

    // ==========================================
    // 9. ПЕРВОНАЧАЛЬНАЯ ЗАГРУЗКА
    // ==========================================

    const hasParamsOnLoad = applyURLParams();

    syncPillsWithForm();

    if (hasParamsOnLoad) {
        fetchAirliners(false, {
            updateHistory: false
        });
    }
});