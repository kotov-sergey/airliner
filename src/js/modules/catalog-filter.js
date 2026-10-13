document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('airliner-filter');
    const resultsContainer = document.getElementById('airliner-results');

    if (!filterForm || !resultsContainer) return;

    // Элементы из твоего тулбара по data-атрибутам
    const countBadge = document.querySelector('[data-catalog-count]');
    const sortSelect = document.querySelector('[data-catalog-sort]');
    const searchInput = document.querySelector('[data-catalog-search]');
    const viewButtons = document.querySelectorAll('[data-catalog-view]');
    const viewInput = document.querySelector('[data-catalog-view-input]');

    const submitBtn = filterForm.querySelector('button[type="submit"]');
    const resetBtn = filterForm.querySelector('button[type="reset"]');

    // Главная функция запроса
    const fetchAirliners = () => {
        const formData = new FormData(filterForm);
        formData.append('action', 'filter_airliners');

        // Блокируем кнопку на время загрузки
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.text = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Загрузка...';
        }
        resultsContainer.style.opacity = '0.3';
        resultsContainer.style.pointerEvents = 'none';

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
                // 1. Обновляем карточки
                resultsContainer.innerHTML = res.data.html;

                // 2. Обновляем счётчик
                if (countBadge) {
                    countBadge.textContent = res.data.count;
                }
            }
        })
        .catch(err => {
            console.error(err);
            resultsContainer.innerHTML = '<p class="error-msg">Ошибка при загрузке бортов.</p>';
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.dataset.text;
            }
            resultsContainer.style.opacity = '1';
            resultsContainer.style.pointerEvents = 'all';
        });
    };

    // 1. Сабмит формы фильтров (кнопка "Применить")
    filterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        fetchAirliners();
    });

    // 2. Мгновенная сортировка при выборе селекта
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            fetchAirliners();
        });
    }

    // 3. Поиск по нажатию клавиши Enter
    if (searchInput) {
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                fetchAirliners();
            }
        });
    }

    // 4. Переключатель вида (Сетка / Таблица)
    if (viewButtons.length > 0) {
        viewButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                viewButtons.forEach(b => b.classList.remove('is-active'));
                btn.classList.add('is-active');

                const viewType = btn.dataset.catalogView; // 'grid' или 'table'

                if (viewInput) viewInput.value = viewType;

                // Меняем класс на контейнере карточек
                if (viewType === 'table') {
                    resultsContainer.classList.add('is-view-table');
                    resultsContainer.classList.remove('is-view-grid');
                } else {
                    resultsContainer.classList.add('is-view-grid');
                    resultsContainer.classList.remove('is-view-table');
                }
            });
        });
    }

    // 5. Кнопка "Сбросить"
    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            filterForm.reset();
            if (searchInput) searchInput.value = '';
            if (sortSelect) sortSelect.selectedIndex = 0;

            setTimeout(() => {
                fetchAirliners();
            }, 50);
        });
    }
});