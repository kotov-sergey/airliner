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
        // Создаем системное событие, чтобы другие компоненты знали об изменении
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
        // Если уже есть в списке — удаляем
        list = list.filter(item => item !== numericId);
    } else {
        // Если еще нет — проверяем лимит
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
 * 5. Обновить состояние интерфейса (Кнопки на карточках и нижний бар)
 */
export function updateCompareUI() {
    const list = getCompareList();
    const count = list.length;

    // А. Подсвечиваем активные кнопки на карточках
    const allButtons = document.querySelectorAll('.js-compare-btn');
    allButtons.forEach(btn => {
        const btnId = Number(btn.dataset.id);
        const textSpan = btn.querySelector('.js-compare-btn-text');

        if (list.includes(btnId)) {
            btn.classList.add('is-active');
            if (textSpan) textSpan.textContent = 'В сравнении';
        } else {
            btn.classList.remove('is-active');
            if (textSpan) textSpan.textContent = 'Сравнить';
        }
    });

    // Б. Обновляем плавающий нижний бар
    const bar = document.querySelector('.js-compare-bar');
    const countEl = document.querySelector('.js-compare-count');
    const submitLink = document.querySelector('.js-compare-submit-link');

    if (bar && countEl && submitLink) {
        countEl.textContent = count;

        if (count > 0) {
            bar.classList.add('is-visible');
            bar.setAttribute('aria-hidden', 'false');
            // Формируем ссылку вида /compare/?ids=104,218
            submitLink.href = `${COMPARE_PAGE_URL}?ids=${list.join(',')}`;
        } else {
            bar.classList.remove('is-visible');
            bar.setAttribute('aria-hidden', 'true');
            submitLink.href = '#';
        }
    }
}

/**
 * 6. Инициализация слушателей событий (Делегирование событий)
 */
export function initCompare() {
    // Используем делегирование событий: клики будут работать даже если карточки подгружены через AJAX!
    document.addEventListener('click', (e) => {
        // Клик по кнопке на карточке
        const btn = e.target.closest('.js-compare-btn');
        if (btn) {
            e.preventDefault();
            const id = btn.dataset.id;
            if (id) {
                toggleCompare(id);
            }
            return;
        }

        // Клик по кнопке "Очистить" в баре
        const clearBtn = e.target.closest('.js-compare-clear');
        if (clearBtn) {
            e.preventDefault();
            clearCompare();
            return;
        }
    });

    // Слушаем кастомное событие обновления данных
    window.addEventListener('compareUpdated', updateCompareUI);

    // Первичный запуск при загрузке страницы
    updateCompareUI();
}