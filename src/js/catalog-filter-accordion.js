document.addEventListener('DOMContentLoaded', () => {
    // Находим все заголовки фильтров
    const filterTitles = document.querySelectorAll('.catalog-filter__title');

    filterTitles.forEach(title => {
        // Вешаем событие клика на каждый заголовок
        title.addEventListener('click', function() {
            // Находим родительский блок именно этого фильтра
            const block = this.closest('.catalog-filter__block');
            
            // Переключаем класс (если есть - уберет, если нет - добавит)
            if (block) {
                block.classList.toggle('is-open');
            }
        });
    });
});