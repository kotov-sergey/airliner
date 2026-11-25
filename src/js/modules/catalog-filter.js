
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

    const rangeFields = [
        'range_min',
        'range_max',
        'passengers_min',
        'passengers_max'
    ];

    let replaceController = null;
    let appendController = null;

    let replaceRequestId = 0;
    let appendRequestId = 0;

    let isAppendLoading = false;
    let suppressResetHandler = false;

    // ==========================================
    // 1. ДВОЙНЫЕ RANGE-СЛАЙДЕРЫ
    // ==========================================

    const rangeSliders = [];

    const clamp = (value, min, max) => {
        return Math.min(Math.max(value, min), max);
    };

    const initRangeSliders = () => {
        const sliders = filterForm.querySelectorAll('[data-range-slider]');

        sliders.forEach(slider => {
            const minFieldName = slider.dataset.minField;
            const maxFieldName = slider.dataset.maxField;

            if (!minFieldName || !maxFieldName) return;

            const minField = filterForm.querySelector(
                `input[name="${minFieldName}"]`
            );

            const maxField = filterForm.querySelector(
                `input[name="${maxFieldName}"]`
            );

            const minRange = slider.querySelector('[data-range-min]');
            const maxRange = slider.querySelector('[data-range-max]');

            const progress = slider.querySelector(
                '.catalog-range__progress'
            );

            if (!minField || !maxField || !minRange || !maxRange) return;

            const minLimit = Number(minRange.min);
            const maxLimit = Number(minRange.max);

            const step = Number(minRange.step) || 1;

            // Округление используется только для бегунков.
            // Числовые поля сохраняют точные значения.
            const normalize = value => {
                const clamped = clamp(value, minLimit, maxLimit);

                const normalized = minLimit +
                    Math.round((clamped - minLimit) / step) * step;

                return clamp(normalized, minLimit, maxLimit);
            };

            const getFieldValue = (field, fallback) => {
                if (field.value === '') return fallback;

                const value = Number(field.value);

                if (!Number.isFinite(value)) return fallback;

                return clamp(value, minLimit, maxLimit);
            };

            // ------------------------------------------
            // Обновление выделенной области слайдера.
            // ------------------------------------------

            const updateProgress = () => {
                if (!progress) return;

                const minValue = Number(minRange.value);
                const maxValue = Number(maxRange.value);

                const range = maxLimit - minLimit;

                if (range <= 0) {
                    progress.style.left = '0%';
                    progress.style.width = '100%';
                    return;
                }

                const left = ((minValue - minLimit) / range) * 100;
                const right = ((maxValue - minLimit) / range) * 100;

                progress.style.left = `${left}%`;
                progress.style.width = `${right - left}%`;
            };

            // ------------------------------------------
            // Синхронизация бегунков с числовыми полями.
            // ------------------------------------------

            const syncFromFields = () => {
                let minValue = getFieldValue(minField, minLimit);
                let maxValue = getFieldValue(maxField, maxLimit);

                // Защита от некорректного диапазона.
                if (minValue > maxValue) {
                    maxValue = minValue;
                    maxField.value = maxValue;
                }

                minRange.value = normalize(minValue);
                maxRange.value = normalize(maxValue);

                updateProgress();
            };

            // ------------------------------------------
            // Перемещение нижнего бегунка.
            // ------------------------------------------

            minRange.addEventListener('input', () => {
                const value = normalize(Number(minRange.value));
                const maxValue = Number(maxRange.value);

                const newValue = Math.min(value, maxValue);

                minRange.value = newValue;
                minField.value = newValue;

                updateProgress();
            });

            // ------------------------------------------
            // Перемещение верхнего бегунка.
            // ------------------------------------------

            maxRange.addEventListener('input', () => {
                const value = normalize(Number(maxRange.value));
                const minValue = Number(minRange.value);

                const newValue = Math.max(value, minValue);

                maxRange.value = newValue;
                maxField.value = newValue;

                updateProgress();
            });

            // ------------------------------------------
            // Ручной ввод минимального значения.
            // ------------------------------------------

            minField.addEventListener('input', () => {
                if (minField.value === '') {
                    syncFromFields();
                    return;
                }

                const value = Number(minField.value);

                if (!Number.isFinite(value)) return;

                const newValue = clamp(value, minLimit, maxLimit);

                // Во время набора не изменяем числовые поля.
                // Только обновляем положение бегунка.
                minRange.value = Math.min(
                    normalize(newValue),
                    Number(maxRange.value)
                );

                updateProgress();
            });

            // Проверка минимального значения после завершения ввода.
            minField.addEventListener('change', () => {
                if (minField.value === '') {
                    syncFromFields();
                    return;
                }

                const value = Number(minField.value);

                if (!Number.isFinite(value)) return;

                let newValue = clamp(value, minLimit, maxLimit);

                const maxValue = getFieldValue(maxField, maxLimit);

                // Минимум не может превышать максимум.
                if (newValue > maxValue) {
                    newValue = maxValue;
                }

                minField.value = newValue;

                syncFromFields();
            });

            // ------------------------------------------
            // Ручной ввод максимального значения.
            // ------------------------------------------

            maxField.addEventListener('input', () => {
                if (maxField.value === '') {
                    syncFromFields();
                    return;
                }

                const value = Number(maxField.value);

                if (!Number.isFinite(value)) return;

                const newValue = clamp(value, minLimit, maxLimit);

                // Во время набора не изменяем числовые поля.
                // Только обновляем положение бегунка.
                maxRange.value = Math.max(
                    normalize(newValue),
                    Number(minRange.value)
                );

                updateProgress();
            });

            // Проверка максимального значения после завершения ввода.
            maxField.addEventListener('change', () => {
                if (maxField.value === '') {
                    syncFromFields();
                    return;
                }

                const value = Number(maxField.value);

                if (!Number.isFinite(value)) return;

                let newValue = clamp(value, minLimit, maxLimit);

                const minValue = getFieldValue(minField, minLimit);

                // Максимум не может быть меньше минимума.
                if (newValue < minValue) {
                    newValue = minValue;
                }

                maxField.value = newValue;

                syncFromFields();
            });

            rangeSliders.push(syncFromFields);

            syncFromFields();
        });
    };

    // Обновление всех слайдеров без повторной регистрации событий.
    const syncRangeSliders = () => {
        rangeSliders.forEach(sync => sync());
    };

    // ==========================================
    // 2. URL И HISTORY API
    // ==========================================

    const updateURL = () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        // Чекбоксы.
        allowedGroups.forEach(group => {
            const values = formData.getAll(`${group}[]`);

            if (values.length) {
                params.set(group, values.join(','));
            }
        });

        // Четыре числовых поля.
        rangeFields.forEach(field => {
            const value = formData.get(field);

            if (value !== null && value !== '') {
                params.set(field, value);
            }
        });

        // Поиск.
        const search = formData.get('search');

        if (typeof search === 'string' && search.trim()) {
            params.set('search', search.trim());
        }

        // Сортировка.
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

        // Восстанавливаем четыре числовых поля.
        rangeFields.forEach(field => {
            if (!params.has(field)) return;

            const input = filterForm.querySelector(
                `input[name="${field}"]`
            );

            if (!input) return;

            const value = params.get(field);

            if (value === '' || !Number.isFinite(Number(value))) return;

            input.value = value;
            hasFilterParams = true;
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
    // 3. ИНДИКАЦИЯ ЗАГРУЗКИ
    // ==========================================

    const setLoadMoreLoading = loading => {
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

    const setResultsLoading = loading => {
        resultsContainer.style.opacity = loading ? '0.3' : '1';

        resultsContainer.setAttribute(
            'aria-busy',
            loading ? 'true' : 'false'
        );
    };

    const updateLoadMore = data => {
        if (!loadMoreBtn || !loadMoreBtn.parentElement) return;

        const currentPage = Number(data.currentPage) || 1;
        const maxPages = Number(data.max_pages) || 0;

        loadMoreBtn.dataset.page = currentPage;
        loadMoreBtn.dataset.maxPages = maxPages;

        loadMoreBtn.parentElement.style.display =
            currentPage >= maxPages ? 'none' : '';
    };

    // ==========================================
    // 4. AJAX ЗАПРОСЫ
    // ==========================================

    const fetchAirliners = async (
        isAppend = false,
        { updateHistory = true } = {}
    ) => {
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

        } finally {
            const isCurrentRequest = isAppend
                ? requestId === appendRequestId
                : requestId === replaceRequestId;

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
    // 5. СИНХРОНИЗАЦИЯ PILLS
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
    // 6. СОБЫТИЯ ФОРМЫ
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

        setTimeout(() => {
            syncRangeSliders();
            syncPillsWithForm();

            fetchAirliners(false);
        }, 0);
    });

    // ==========================================
    // 7. ПОКАЗАТЬ ЕЩЁ
    // ==========================================

    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            fetchAirliners(true);
        });
    }

    // ==========================================
    // 8. ИСТОРИЯ БРАУЗЕРА
    // ==========================================

    window.addEventListener('popstate', () => {
        suppressResetHandler = true;

        filterForm.reset();

        suppressResetHandler = false;

        // Восстанавливаем значения из URL.
        applyURLParams();

        syncRangeSliders();
        syncPillsWithForm();

        fetchAirliners(false, {
            updateHistory: false
        });
    });

    // ==========================================
    // 9. БЫСТРЫЕ ТАБЛЕТКИ
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
    // 10. ПЕРВОНАЧАЛЬНАЯ ЗАГРУЗКА
    // ==========================================

    // Инициализируем слайдеры один раз.
    initRangeSliders();

    // Восстанавливаем параметры URL.
    const hasParamsOnLoad = applyURLParams();

    syncRangeSliders();
    syncPillsWithForm();

    if (hasParamsOnLoad) {
        fetchAirliners(false, {
            updateHistory: false
        });
    }
});