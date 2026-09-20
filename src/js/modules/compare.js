// Скрипт обработки и вывода выбранных авиалайнеров
const STORAGE_KEY = 'airliner_compare_ids';
const MAX_ITEMS = 4;
const COMPARE_PAGE_URL = '/compare/'; // ярлык вашей страницы сравнения

/**
 * 1. Получить текущий массив ID из LocalStorage
 */
export function getCompareList() {
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];
    } catch (e) {
        console.error('Ошибка чтения LocalStorage:', e);
        return [];
    }
}

/**
 * 2. Сохранить массив ID в LocalStorage
 */
function saveCompareList(ids) {
    try {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
        window.dispatchEvent(new Event('compareUpdated'));
    } catch (e) {
        console.error('Ошибка записи в LocalStorage:', e);
    }
}

/**
 * 3. Добавить или удалить ID из списка
 */
export function toggleCompare(id) {
    const numericId = Number(id);
    let list = getCompareList();

    if (list.includes(numericId)) {
        list = list.filter(item => item !== numericId);
    } else {
        if (list.length >= MAX_ITEMS) {
            alert(`Можно сравнить не более ${MAX_ITEMS} лайнеров одновременно.`);
            return false;
        }
        list.push(numericId);
    }

    saveCompareList(list);
    return true;
}

/**
 * 4. Очистить весь список
 */
export function clearCompare() {
    saveCompareList([]);
}

/**
 * 5. Обновить состояние интерфейса
 */
export function updateCompareUI() {
    const list = getCompareList();
    const count = list.length;

    // А. Подсвечиваем активные кнопки на карточках
    const allButtons = document.querySelectorAll('.js-compare-btn');
    allButtons.forEach(btn => {
        const btnId = Number(btn.dataset.id);
        
        // Короткая и современная запись toggle для классов
        btn.classList.toggle('is-active', list.includes(btnId));
    });

    // Б. Обновляем плавающий нижний бар
    const bar = document.querySelector('.js-compare-bar');
    const countEl = document.querySelector('.js-compare-count');
    const submitLink = document.querySelector('.js-compare-submit-link');

    if (bar && countEl && submitLink) {
        countEl.textContent = count;

        if (count > 0) {
            // Показываем панель, если есть хотя бы 1 лайнер
            bar.classList.add('is-visible');
            bar.setAttribute('aria-hidden', 'false');

            // --- НОВАЯ ЛОГИКА ДЛЯ КНОПКИ СРАВНИТЬ ---
            if (count === 1) {
                // Если лайнер только 1: отключаем кнопку
                submitLink.href = 'javascript:void(0)';
                submitLink.classList.add('is-disabled'); // Для стилей
                submitLink.style.pointerEvents = 'none'; // Запрещаем клик
                submitLink.style.opacity = '0.5'; // Визуально глушим
                submitLink.textContent = 'Выберите еще один'; // Опционально: можно менять текст
            } else {
                // Если 2 и более: активируем кнопку
                submitLink.href = `${COMPARE_PAGE_URL}?ids=${list.join(',')}`;
                submitLink.classList.remove('is-disabled');
                submitLink.style.pointerEvents = 'auto'; // Разрешаем клик
                submitLink.style.opacity = '1';
                submitLink.textContent = 'Сравнить'; // Возвращаем текст
            }

        } else {
            bar.classList.remove('is-visible');
            bar.setAttribute('aria-hidden', 'true');
        }
    }
}

/**
 * 6. Инициализация слушателей событий
 */
export function initCompare() {
    document.addEventListener('click', (e) => {
        
        // Клик по кнопке "Добавить" на карточке
        const btn = e.target.closest('.js-compare-btn');
        if (btn) {
            e.preventDefault();
            const id = btn.dataset.id;
            if (id) toggleCompare(id);
            return;
        }

        // Клик по кнопке "Очистить" в баре
        const clearBtn = e.target.closest('.js-compare-clear');
        if (clearBtn) {
            e.preventDefault();
            clearCompare();
            return;
        }

        // --- НОВАЯ ЛОГИКА: КЛИК ПО КРЕСТИКУ НА СТРАНИЦЕ СРАВНЕНИЯ ---
        const removeBtn = e.target.closest('.js-compare-remove');
        if (removeBtn) {
            e.preventDefault();
            const id = removeBtn.dataset.id;
            
            if (id) {
                // 1. Удаляем из LocalStorage
                toggleCompare(id);
                const currentList = getCompareList();

                // 2. ПРОВЕРКА НА КОЛИЧЕСТВО (Меньше 2)
                if (currentList.length < 2) {
                    // Находим обертку таблицы
                    const tableWrapper = document.querySelector('.compare-table-wrapper');
                    
                    if (tableWrapper) {
                        // Меняем HTML таблицы на твою заглушку!
                        // (Убедись, что ссылка ведет на твой актуальный каталог)
                        tableWrapper.outerHTML = `
                            <div class="compare-empty" style="padding: var(--space-12) 0;">
                                <p class="text-secondary" style="font-size: var(--text-lg); margin-bottom: var(--space-6);">
                                    Для сравнения выберите как минимум 2 самолета из каталога.
                                </p>
                                <a href="/airliners/" class="btn btn--primary">Перейти в каталог</a>
                            </div>
                        `;
                    }

                    // Обновляем URL (оставляем 1 ID или очищаем, но без перезагрузки)
                    const newUrl = currentList.length === 1 
                        ? `${COMPARE_PAGE_URL}?ids=${currentList[0]}` 
                        : COMPARE_PAGE_URL;
                    window.history.replaceState({}, '', newUrl);

                } else {
                    // 3. ЕСЛИ САМОЛЕТОВ 2 ИЛИ БОЛЬШЕ (Обычное удаление колонки)
                    const cell = removeBtn.closest('td, th');
                    if (cell) {
                        const colIndex = cell.cellIndex;
                        const table = cell.closest('table');
                        const rows = table.querySelectorAll('tr');
                        rows.forEach(row => {
                            if (row.children[colIndex]) {
                                row.children[colIndex].remove();
                            }
                        });
                    }

                    // Обновляем URL
                    const newUrl = `${COMPARE_PAGE_URL}?ids=${currentList.join(',')}`;
                    window.history.replaceState({}, '', newUrl);
                }
            }
            return;
        }
    });

    window.addEventListener('compareUpdated', updateCompareUI);
    updateCompareUI();
}