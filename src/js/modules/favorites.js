// src/js/modules/favorites.js

const STORAGE_KEY = 'airliner_favorite_ids';
const MAX_ITEMS = 4;
const FAVORITES_PAGE_URL = '/favorites/';

// Получение списка ID из localStorage.
export function getFavoritesList() {
    try {
        const data = localStorage.getItem(STORAGE_KEY);
        const list = data ? JSON.parse(data) : [];

        return Array.isArray(list) ? list : [];
    } catch {
        return [];
    }
}

// Сохранение списка ID в localStorage.
function saveFavoritesList(ids) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(ids));
    window.dispatchEvent(new Event('favoritesUpdated'));
}

// Добавление или удаление авиалайнера из избранного.
export function toggleFavorite(id) {
    const numericId = Number(id);

    if (!Number.isInteger(numericId) || numericId <= 0) {
        return false;
    }

    let list = getFavoritesList();

    if (list.includes(numericId)) {
        list = list.filter(item => item !== numericId);
    } else {
        if (list.length >= MAX_ITEMS) {
            alert(`Можно добавить в избранное не более ${MAX_ITEMS} авиалайнеров.`);
            return false;
        }

        list.push(numericId);
    }

    saveFavoritesList(list);

    return true;
}

// Удаление авиалайнера из избранного.
export function removeFavorite(id) {
    const numericId = Number(id);

    if (!Number.isInteger(numericId) || numericId <= 0) {
        return false;
    }

    const list = getFavoritesList();
    const updatedList = list.filter(item => item !== numericId);

    if (updatedList.length === list.length) {
        return false;
    }

    saveFavoritesList(updatedList);

    return true;
}

// Очистка списка избранного.
export function clearFavorites() {
    saveFavoritesList([]);
}

// Обновление интерфейса избранного.
export function updateFavoritesUI() {
    const list = getFavoritesList();
    const count = list.length;

    // Обновление счётчика в шапке.
    document.querySelectorAll('[data-favorites-count]').forEach(counter => {
        counter.textContent = count;
    });

    // Обновление ссылки на страницу избранного.
    document.querySelectorAll('[data-favorites-link]').forEach(link => {
        const url = new URL(link.href);

        if (list.length) {
            url.searchParams.set('ids', list.join(','));
        } else {
            url.searchParams.delete('ids');
        }

        link.href = url.toString();
    });

    // Обновление кнопок избранного на карточках.
    document.querySelectorAll('.js-favorite-btn').forEach(btn => {
        const btnId = Number(btn.dataset.id);
        const isActive = list.includes(btnId);

        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-pressed', String(isActive));

        const label = btn.querySelector('.js-favorite-btn-text');

        if (label) {
            label.textContent = isActive ? 'В избранном' : 'В избранное';
        }

        btn.setAttribute(
            'aria-label',
            isActive ? 'Удалить из избранного' : 'Добавить в избранное'
        );
    });
}

// Инициализация модуля избранного.
export function initFavorites() {
    document.addEventListener('click', (e) => {
        // Добавление или удаление из избранного на карточке.
        const btn = e.target.closest('.js-favorite-btn');

        if (btn) {
            e.preventDefault();
            toggleFavorite(btn.dataset.id);
            return;
        }

        // Удаление авиалайнера на странице избранного.
        const removeBtn = e.target.closest('.js-favorite-remove');

        if (removeBtn) {
            e.preventDefault();

            const isRemoved = removeFavorite(removeBtn.dataset.id);

            if (isRemoved) {
                const list = getFavoritesList();

                window.location.href = list.length > 0
                    ? `${FAVORITES_PAGE_URL}?ids=${list.join(',')}`
                    : FAVORITES_PAGE_URL;
            }
        }
    });

    // Обновление интерфейса при изменении списка в текущей вкладке.
    window.addEventListener('favoritesUpdated', updateFavoritesUI);

    // Синхронизация при изменении localStorage в другой вкладке.
    window.addEventListener('storage', (e) => {
        if (e.key === STORAGE_KEY) {
            updateFavoritesUI();
        }
    });

    // Первоначальная синхронизация интерфейса.
    updateFavoritesUI();
}