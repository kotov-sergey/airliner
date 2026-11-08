export function initCatalogView() {
    const viewButtons = document.querySelectorAll('[data-catalog-view]');

    const resultsContainer =
        document.getElementById('airliner-results') ||
        document.querySelector('.catalog-grid');

    const viewInput = document.querySelector('[data-catalog-view-input]');
    const filterForm = document.getElementById('airliner-filter');

    const storageKey = 'airliner_catalog_view';
    const allowedViews = ['grid', 'table'];

    if (!viewButtons.length || !resultsContainer) return;

    // Получение сохранённого вида
    const getSavedView = () => {
        try {
            const savedView = localStorage.getItem(storageKey);

            return allowedViews.includes(savedView)
                ? savedView
                : 'grid';

        } catch (error) {
            return 'grid';
        }
    };

    // Переключение вида каталога
    const setView = (viewType) => {
        const view = allowedViews.includes(viewType)
            ? viewType
            : 'grid';

        // Обновляем кнопки переключения вида
        viewButtons.forEach(btn => {
            const isActive = btn.dataset.catalogView === view;

            btn.classList.toggle('is-active', isActive);
            btn.setAttribute('aria-pressed', String(isActive));
        });

        // Обновляем скрытое поле формы
        if (viewInput) {
            viewInput.value = view;
        }

        // Переключаем класс контейнера каталога
        const isTable = view === 'table';

        resultsContainer.classList.toggle('is-view-table', isTable);
        resultsContainer.classList.toggle('is-view-grid', !isTable);

        // Обновляем модификаторы карточек
        const cards = resultsContainer.querySelectorAll('.card-aircraft');

        cards.forEach(card => {
            card.classList.toggle('card-aircraft--horizontal', isTable);
            card.classList.toggle('card-aircraft--vertical', !isTable);
        });

        // Сохраняем выбор пользователя
        try {
            localStorage.setItem(storageKey, view);
        } catch (error) {
            // Каталог продолжает работать без localStorage
        }
    };

    // Восстанавливаем сохранённый вид при загрузке
    setView(getSavedView());

    // Обработчики кнопок переключения вида
    viewButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            setView(btn.dataset.catalogView);
        });
    });

    // Восстанавливаем вид после сброса формы фильтров
    if (filterForm) {
        filterForm.addEventListener('reset', () => {
            setTimeout(() => {
                setView(getSavedView());
            }, 0);
        });
    }
}