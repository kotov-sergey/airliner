const STORAGE_KEY = 'airliner_compare_ids';
const MAX_ITEMS = 4;
const COMPARE_PAGE_URL = '/compare/';

export function getCompareList() {
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        return data ? JSON.parse(data) : [];
    } catch {
        return [];
    }
}

function saveCompareList(ids) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    window.dispatchEvent(new Event('compareUpdated'));
}

export function toggleCompare(id) {
    const numericId = Number(id);
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

export function clearCompare() {
    saveCompareList([]);
}

// Обновление интерфейса (только кнопки и нижняя плашка!)
export function updateCompareUI() {
    const list = getCompareList();
    const count = list.length;

    // 1. Подсветка кнопок на карточках в каталоге
    document.querySelectorAll('.js-compare-btn').forEach(btn => {
        const btnId = Number(btn.dataset.id);
        btn.classList.toggle('is-active', list.includes(btnId));
    });

    // 2. Нижняя плашка
    const bar = document.querySelector('.js-compare-bar');
    const countEl = document.querySelector('.js-compare-count');
    const submitLink = document.querySelector('.js-compare-submit-link');

    if (bar && countEl && submitLink) {
        countEl.textContent = count;

        if (count > 0) {
            // Показываем панель, если есть хотя бы 1 лайнер
            bar.classList.add('is-visible');
            bar.setAttribute('aria-hidden', 'false');

            if (count === 1) {
                // Если лайнер только 1: отключаем кнопку
                submitLink.href = 'javascript:void(0)';
                submitLink.classList.add('is-disabled');
                submitLink.style.pointerEvents = 'none';
                submitLink.style.opacity = '0.5';
                submitLink.textContent = 'Выберите еще один';
            } else {
                // Если 2 и более: активируем кнопку
                submitLink.href = `${COMPARE_PAGE_URL}?ids=${list.join(',')}`;
                submitLink.classList.remove('is-disabled');
                submitLink.style.pointerEvents = 'auto';
                submitLink.style.opacity = '1';
                submitLink.textContent = 'Сравнить';
            }

        } else {
            bar.classList.remove('is-visible');
            bar.setAttribute('aria-hidden', 'true');
        }
    }
}

// Простые и понятные клики
export function initCompare() {
    document.addEventListener('click', (e) => {
        
        // 1. Клик по кнопке на карточке самолета
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

        // 3. Клик по крестику в таблице сравнения (БЕЗ КОСТЫЛЕЙ)
        const removeBtn = e.target.closest('.js-compare-remove');
        if (removeBtn) {
            e.preventDefault();
            const id = Number(removeBtn.dataset.id);
            
            // Просто удаляем ID из памяти
            let list = getCompareList().filter(item => item !== id);
            saveCompareList(list);

            // И просто обновляем страницу с новым списком параметров!
            // PHP сам всё перерисует без единой строчки JS-грязи:
            if (list.length >= 2) {
                window.location.href = `${COMPARE_PAGE_URL}?ids=${list.join(',')}`;
            } else {
                // Если остался 1 или 0 — переходим на пустую страницу сравнения (сработает заглушка в PHP)
                window.location.href = COMPARE_PAGE_URL;
            }
        }
    });

    window.addEventListener('compareUpdated', updateCompareUI);
    updateCompareUI();
}