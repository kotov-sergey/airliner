// src/js/modules/compare.js 

import {
    showToast,
    queueToast
} from './notification-toast.js';

const STORAGE_KEY = 'airliner_compare_ids';
const MAX_ITEMS = 4;
const COMPARE_PAGE_URL = '/compare/';

// Получение списка ID из localStorage.
export function getCompareList() {
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        const list = data ? JSON.parse(data) : [];

        return Array.isArray(list) ? list : [];
    } catch {
        return [];
    }
}

// Сохранение списка ID в localStorage.
function saveCompareList(ids) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    window.dispatchEvent(new Event('compareUpdated'));
}

// Добавление или удаление авиалайнера из сравнения.
export function toggleCompare(id) {
    const numericId = Number(id);

    if (!Number.isInteger(numericId) || numericId <= 0) {
        return false;
    }

    let list = getCompareList();

    if (list.includes(numericId)) {
        list = list.filter(item => item !== numericId);
    } else {
        if (list.length >= MAX_ITEMS) {
            alert(`Можно сравнить не более ${MAX_ITEMS} авиалайнеров.`);
            return false;
        }

        list.push(numericId);
    }

    saveCompareList(list);

    return true;
}

// Удаление авиалайнера из сравнения.
export function removeCompare(id) {
    const numericId = Number(id);

    if (!Number.isInteger(numericId) || numericId <= 0) {
        return false;
    }

    const list = getCompareList();
    const updatedList = list.filter(item => item !== numericId);

    if (updatedList.length === list.length) {
        return false;
    }

    saveCompareList(updatedList);

    return true;
}

// Очистка списка сравнения.
export function clearCompare() {
    saveCompareList([]);
}

// Обновление интерфейса сравнения.
export function updateCompareUI() {
    const list = getCompareList();
    const count = list.length;

    // Обновление счётчика в шапке.
    document.querySelectorAll('[data-compare-count]').forEach(counter => {
        counter.textContent = count;
    });

    // Обновление ссылки на страницу сравнения.
    document.querySelectorAll('[data-compare-link]').forEach(link => {
        const url = new URL(link.href);

        if (list.length) {
            url.searchParams.set('ids', list.join(','));
        } else {
            url.searchParams.delete('ids');
        }

        link.href = url.toString();
    });

    // Обновление кнопок сравнения на карточках.
    document.querySelectorAll('.js-compare-btn').forEach(btn => {
        const btnId = Number(btn.dataset.id);
        const isActive = list.includes(btnId);

        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', String(isActive));

        const label = btn.querySelector('.js-compare-btn-text');

        if (label) {
            label.textContent = isActive ? 'В сравнении' : 'Сравнить';
        }

        btn.setAttribute(
            'aria-label',
            isActive ? 'Убрать из сравнения' : 'Добавить к сравнению'
        );
    });
}

// Инициализация модуля сравнения.
export function initCompare() {
    document.addEventListener('click', (e) => {
        // Добавление или удаление из сравнения на карточке.
        const btn = e.target.closest('.js-compare-btn');

        if (btn) {
            e.preventDefault();

            const wasActive = btn.classList.contains('is-active');
            const isChanged = toggleCompare(btn.dataset.id);

            if (!isChanged) return;

            showToast({
                message: wasActive
                    ? 'Авиалайнер удалён из сравнения'
                    : 'Авиалайнер добавлен к сравнению',
                url: COMPARE_PAGE_URL
            });

            return;
        }

        // Удаление авиалайнера непосредственно на странице сравнения.
        const removeBtn = e.target.closest('.js-compare-remove');

        if (removeBtn) {
            e.preventDefault();

            const isRemoved = removeCompare(removeBtn.dataset.id);

            if (!isRemoved) return;

            queueToast({
                message: 'Авиалайнер удалён из сравнения',
                url: COMPARE_PAGE_URL
            });

            const list = getCompareList();

            window.location.href = list.length >= 2
                ? `${COMPARE_PAGE_URL}?ids=${list.join(',')}`
                : COMPARE_PAGE_URL;
        }
    });

    // Обновление интерфейса при изменении списка в текущей вкладке.
    window.addEventListener('compareUpdated', updateCompareUI);

    // Синхронизация при изменении localStorage в другой вкладке.
    window.addEventListener('storage', (e) => {
        if (e.key === STORAGE_KEY) {
            updateCompareUI();

            // На странице сравнения обновляем таблицу.
            if (
                document.body.classList.contains('page-template-page-compare')
            ) {
                window.location.reload();
            }
        }
    });

    // Первоначальная синхронизация интерфейса.
    updateCompareUI();
}