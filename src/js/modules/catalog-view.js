export function initCatalogView() {
    const viewButtons = document.querySelectorAll('[data-catalog-view]');
    // В верстке у тебя контейнер назывался #airliner-results
    const resultsContainer = document.getElementById('airliner-results') || document.querySelector('.catalog-grid');
    const viewInput = document.querySelector('[data-catalog-view-input]');

    if (!viewButtons.length || !resultsContainer) return;

    // Единая функция переключения вида
    const setView = (viewType) => {
        // 1. Переключаем активный класс у кнопок в тулбаре
        viewButtons.forEach(btn => {
            btn.classList.toggle('is-active', btn.dataset.catalogView === viewType);
        });

        // 2. Обновляем значение скрытого инпута для AJAX
        if (viewInput) {
            viewInput.value = viewType;
        }

        // 3. Переключаем класс на контейнере сетки
        if (viewType === 'table') {
            resultsContainer.classList.add('is-view-table');
            resultsContainer.classList.remove('is-view-grid');
        } else {
            resultsContainer.classList.add('is-view-grid');
            resultsContainer.classList.remove('is-view-table');
        }

        // 4. Меняем модификаторы у всех карточек
        const cards = resultsContainer.querySelectorAll('.card-aircraft');
        cards.forEach(card => {
            if (viewType === 'table') {
                card.classList.remove('card-aircraft--vertical');
                card.classList.add('card-aircraft--horizontal');
            } else {
                card.classList.remove('card-aircraft--horizontal');
                card.classList.add('card-aircraft--vertical');
            }
        });

        // 5. Запоминаем выбор пользователя в браузере!
        localStorage.setItem('airliner_catalog_view', viewType);
    };

    // При первой загрузке страницы: восстанавливаем сохраненный вид или ставим 'grid'
    const savedView = localStorage.getItem('airliner_catalog_view') || 'grid';
    setView(savedView);

    // Вешаем клик на кнопки
    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const viewType = btn.dataset.catalogView; // 'grid' или 'table'
            setView(viewType);
        });
    });
}