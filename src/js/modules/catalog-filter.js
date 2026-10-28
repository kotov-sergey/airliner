document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('airliner-filter');
    const resultsContainer = document.getElementById('airliner-results');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const countBadge = document.querySelector('[data-catalog-count]');

    if (!filterForm || !resultsContainer) return;

    const sortSelect = document.querySelector('[data-catalog-sort]');
    const searchInput = document.querySelector('[data-catalog-search]');

    // ==========================================
    // 1. ФУНКЦИИ РАБОТЫ С URL (HISTORY API)
    // ==========================================

    // Запись текущих фильтров в URL браузера
    const updateURL = () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        // 1. Чекбоксы (собираем в красивый вид: brand=12,15)
        const brands = formData.getAll('brand[]');
        if (brands.length) params.set('brand', brands.join(','));

        const fuselages = formData.getAll('fuselage[]');
        if (fuselages.length) params.set('fuselage', fuselages.join(','));

        const statuses = formData.getAll('status[]');
        if (statuses.length) params.set('status', statuses.join(','));

        // 2. Числовые поля
        const range = formData.get('range');
        if (range) params.set('range', range);

        const passengers = formData.get('passengers');
        if (passengers) params.set('passengers', passengers);

        // 3. Поиск
        const search = formData.get('search');
        if (search && search.trim()) params.set('search', search.trim());

        // 4. Сортировка (пишем в URL только если это не дефолт)
        const sort = formData.get('sort');
        if (sort && sort !== 'popular') params.set('sort', sort);

        // Формируем чистый URL
        const queryString = params.toString();
        const newUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;

        // Обновляем адресную строку без перезагрузки страницы
        window.history.pushState({ path: newUrl }, '', newUrl);
    };

    // Чтение параметров из URL и простановка галочек в форме
    const applyURLParams = () => {
        const params = new URLSearchParams(window.location.search);
        let hasFilterParams = false;

        // Восстанавливаем чекбоксы
        ['brand', 'fuselage', 'status'].forEach(tax => {
            if (params.has(tax)) {
                hasFilterParams = true;
                const values = params.get(tax).split(',');
                values.forEach(val => {
                    const input = filterForm.querySelector(`input[name="${tax}[]"][value="${val}"]`);
                    if (input) input.checked = true;
                });
            }
        });

        // Восстанавливаем числа
        ['range', 'passengers'].forEach(field => {
            if (params.has(field)) {
                hasFilterParams = true;
                const input = filterForm.querySelector(`input[name="${field}"]`);
                if (input) input.value = params.get(field);
            }
        });

        // Восстанавливаем поиск
        if (params.has('search') && searchInput) {
            hasFilterParams = true;
            searchInput.value = params.get('search');
        }

        // Восстанавливаем сортировку
        if (params.has('sort') && sortSelect) {
            hasFilterParams = true;
            sortSelect.value = params.get('sort');
        }

        return hasFilterParams;
    };


    // ==========================================
    // 2. AJAX ЗАПРОС
    // ==========================================
    const fetchAirliners = (isAppend = false) => {
        const formData = new FormData(filterForm);
        formData.append('action', 'filter_airliners');

        // Страница пагинации
        let currentPage = 1;
        if (isAppend && loadMoreBtn) {
            currentPage = parseInt(loadMoreBtn.dataset.page, 10) + 1;
        }
        formData.append('page', currentPage);

        // Индикация загрузки
        if (isAppend && loadMoreBtn) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.querySelector('.load-more-btn__text').style.display = 'none';
            loadMoreBtn.querySelector('.load-more-btn__loader').style.display = 'inline';
        } else {
            resultsContainer.style.opacity = '0.3';
            // Обновляем URL только при новой фильтрации, а не при кнопке "Показать ещё"
            updateURL();
        }

        const ajaxUrl = window.wpData?.ajaxUrl || '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Ошибка сети');
            return response.json();
        })
        .then(res => {
            if (res.success) {
                // 1. Вставляем HTML
                if (isAppend) {
                    resultsContainer.insertAdjacentHTML('beforeend', res.data.html);
                } else {
                    resultsContainer.innerHTML = res.data.html;
                }

                // 2. Обновляем счётчик
                if (countBadge) {
                    countBadge.textContent = res.data.count;
                }

                // 3. Кнопка "Показать ещё"
                if (loadMoreBtn && loadMoreBtn.parentElement) {
                    loadMoreBtn.dataset.page = res.data.currentPage;
                    loadMoreBtn.dataset.maxPages = res.data.max_pages;

                    if (res.data.currentPage >= res.data.max_pages) {
                        loadMoreBtn.parentElement.style.display = 'none';
                    } else {
                        loadMoreBtn.parentElement.style.display = ''; // сброс до flex из CSS
                    }
                }
            }
        })
        .catch(err => {
            console.error('Ошибка AJAX:', err);
        })
        .finally(() => {
            if (loadMoreBtn) {
                loadMoreBtn.disabled = false;
                loadMoreBtn.querySelector('.load-more-btn__text').style.display = 'inline';
                loadMoreBtn.querySelector('.load-more-btn__loader').style.display = 'none';
            }
            resultsContainer.style.opacity = '1';
        });
    };


    // ==========================================
    // 3. СОБЫТИЯ
    // ==========================================

    // 1. Сабмит фильтров
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchAirliners(false);
    });

    // 2. Смена сортировки
    if (sortSelect) {
        sortSelect.addEventListener('change', () => fetchAirliners(false));
    }

    // 3. Поиск по Enter
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                fetchAirliners(false);
            }
        });
    }

    // 4. Кнопка "Показать ещё"
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', () => {
            fetchAirliners(true);
        });
    }

    // 5. Кнопка "Сбросить"
    filterForm.addEventListener('reset', () => {
        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.selectedIndex = 0;

        setTimeout(() => {
            fetchAirliners(false);
        }, 20);
    });

    // 6. Поддержка кнопок браузера «Назад» и «Вперед»
    window.addEventListener('popstate', () => {
        // Очищаем форму, читаем параметры из URL и перезагружаем
        filterForm.reset();
        if (searchInput) searchInput.value = '';
        if (sortSelect) sortSelect.selectedIndex = 0;

        applyURLParams();
        fetchAirliners(false);
    });


    // ==========================================
    // 4. СТАРТ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ
    // ==========================================
    // Если пользователь пришел по ссылке с параметрами (например: ?brand=12)
    const hasParamsOnLoad = applyURLParams();
    if (hasParamsOnLoad) {
        // Автоматически запускаем фильтрацию под параметры из ссылки!
        fetchAirliners(false);
    }

    // ==========================================
    // 5. ИНТЕГРАЦИЯ БЫСТРЫХ ТАБЛЕТОК (PILLS)
    // ==========================================

    // Функция обновления визуального состояния таблеток на основе галочек в форме
    const syncPillsWithForm = () => {
        ['brand', 'fuselage'].forEach(group => {
            const checkedInputs = filterForm.querySelectorAll(`input[name="${group}[]"]:checked`);
            const checkedIds = Array.from(checkedInputs).map(input => input.value);

            const allPill = document.querySelector(`[data-pill-all="${group}"]`);
            const itemPills = document.querySelectorAll(`[data-pill-group="${group}"]`);

            // Если ничего не выбрано — активна кнопка "Все"
            if (checkedIds.length === 0) {
                if (allPill) allPill.classList.add('is-active');
                itemPills.forEach(pill => pill.classList.remove('is-active'));
            } else {
                if (allPill) allPill.classList.remove('is-active');
                itemPills.forEach(pill => {
                    const id = pill.dataset.termId;
                    pill.classList.toggle('is-active', checkedIds.includes(id));
                });
            }
        });
    };

    // Слушаем клики по таблеткам
    document.addEventListener('click', (e) => {
        // 1. Клик по кнопке "Все бренды" или "Все типы"
        const allBtn = e.target.closest('[data-pill-all]');
        if (allBtn) {
            e.preventDefault();
            const group = allBtn.dataset.pillAll; // 'brand' или 'fuselage'

            // Снимаем все галочки в сайдбаре для этой группы
            const inputs = filterForm.querySelectorAll(`input[name="${group}[]"]`);
            inputs.forEach(input => input.checked = false);

            syncPillsWithForm();
            fetchAirliners(false);
            return;
        }

        // 2. Клик по конкретной таблетке (например, "Boeing")
        const itemBtn = e.target.closest('[data-pill-item]');
        if (itemBtn) {
            e.preventDefault();
            const group = itemBtn.dataset.pillGroup;
            const termId = itemBtn.dataset.termId;

            // Находим чекбокс в сайдбаре
            const checkbox = filterForm.querySelector(`input[name="${group}[]"][value="${termId}"]`);
            if (checkbox) {
                // Переключаем галочку: если была нажата — снимаем, если нет — ставим
                checkbox.checked = !checkbox.checked;
            }

            syncPillsWithForm();
            fetchAirliners(false);
        }
    });

    // При вызове reset или загрузке страницы синхронизируем таблетки
    filterForm.addEventListener('reset', () => {
        setTimeout(syncPillsWithForm, 30);
    });

    // Первичная синхронизация при загрузке
    syncPillsWithForm();
});