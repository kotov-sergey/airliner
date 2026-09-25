// src/js/modules/compare.js

const STORAGE_KEY = 'airliner_compare_ids';
const MAX_ITEMS = 4;
const COMPARE_PAGE_URL = '/compare/';

/**
 * 1. Получить текущий массив ID из LocalStorage
 */
export function getCompareList() {
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];
    } catch {
        return [];
    }
}

/**
 * 2. Сохранить массив ID в LocalStorage и уведомить приложение
 */
function saveCompareList(ids) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    window.dispatchEvent(new Event('compareUpdated'));
}

/**
 * 3. Переключатель: добавить или удалить ID из списка
 */
export function toggleCompare(id) {
    const numericId = Number(id);
    if (!numericId) return false;

    let list = getCompareList();

    if (list.includes(numericId)) {
        list = list.filter(item => item !== numericId);
    } else {
        if (list.length >= MAX_ITEMS) {
            alert(`Можно сравнить не более ${MAX_ITEMS} лайнеров.`);
            return false;
        }
        list.push(numericId);
    }

    saveCompareList(list);
    return true;
}

/**
 * 4. Полная очистка списка
 */
export function clearCompare() {
    saveCompareList([]);
}

/**
 * 5. Обновление интерфейса (Кнопки карточек и нижний бар)
 */
export function updateCompareUI() {
    const list = getCompareList();
    const count = list.length;

    // А. Подсветка кнопок в каталоге
    document.querySelectorAll('.js-compare-btn').forEach(btn => {
        const btnId = Number(btn.dataset.id);
        const isActive = list.includes(btnId);

        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', String(isActive));

        // Если в кнопке есть текст — меняем его
        const label = btn.querySelector('.js-compare-btn-text');
        if (label) {
            label.textContent = isActive ? 'В сравнении' : 'Сравнить';
        }
    });

    // Б. Обновление плавающей панели внизу
    const bar = document.querySelector('.js-compare-bar');
    const countEl = document.querySelector('.js-compare-count');
    const submitLink = document.querySelector('.js-compare-submit-link');

    if (!bar || !countEl || !submitLink) return;

    countEl.textContent = count;
    bar.classList.toggle('is-visible', count > 0);
    bar.setAttribute('aria-hidden', String(count === 0));

    // Логика состояний кнопки перехода
    if (count >= 2) {
        submitLink.href = `${COMPARE_PAGE_URL}?ids=${list.join(',')}`;
        submitLink.classList.remove('btn--disabled');
        submitLink.removeAttribute('aria-disabled');
        submitLink.textContent = 'Сравнение';
    } else {
        submitLink.href = 'javascript:void(0);';
        submitLink.classList.add('btn--disabled');
        submitLink.setAttribute('aria-disabled', 'true');
        submitLink.textContent = 'Выберите еще один';
    }
}

/**
 * 6. Слушатели кликов
 */
export function initCompare() {
    document.addEventListener('click', (e) => {
        
        // 1. Клик "Сравнить" на карточке самолета
        const btn = e.target.closest('.js-compare-btn');
        if (btn) {
            e.preventDefault();
            toggleCompare(btn.dataset.id);
            return;
        }

        // 2. Клик "Очистить" в нижнем баре
        const clearBtn = e.target.closest('.js-compare-clear');
        if (clearBtn) {
            e.preventDefault();
            clearCompare();
            return;
        }

        // 3. Клик по крестику удаления (на странице сравнения)
        const removeBtn = e.target.closest('.js-compare-remove');
        if (removeBtn) {
            e.preventDefault();
            
            // Удаляем ID через общий метод
            toggleCompare(removeBtn.dataset.id);

            // Перезагружаем страницу с актуальными параметрами (чистый SSR)
            const list = getCompareList();
            window.location.href = list.length >= 2 
                ? `${COMPARE_PAGE_URL}?ids=${list.join(',')}` 
                : COMPARE_PAGE_URL;
        }
    });

    // Реакция на обновление данных
    window.addEventListener('compareUpdated', updateCompareUI);
    
    // Запуск при инициализации
    updateCompareUI();
}