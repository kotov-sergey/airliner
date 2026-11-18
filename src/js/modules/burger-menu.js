// Мобильная навигация сайта

document.addEventListener('DOMContentLoaded', () => {
    const burger = document.querySelector('.header__burger');
    const nav = document.querySelector('.header__nav');

    if (!burger || !nav) return;

    const desktopMedia = window.matchMedia('(min-width: 1024px)');

    // Открытие и закрытие меню
    const toggleMenu = (isOpen) => {
        burger.classList.toggle('active', isOpen);
        nav.classList.toggle('active', isOpen);

        burger.setAttribute('aria-expanded', String(isOpen));
        burger.setAttribute(
            'aria-label',
            isOpen ? 'Закрыть меню' : 'Открыть меню'
        );

        // Блокировка взаимодействия с закрытым меню
        nav.inert = !isOpen;
    };

    // Начальное состояние
    const syncMenu = () => {
        if (desktopMedia.matches) {
            // На компьютере меню всегда доступно
            burger.classList.remove('active');
            nav.classList.remove('active');

            burger.setAttribute('aria-expanded', 'false');
            burger.setAttribute('aria-label', 'Открыть меню');

            nav.inert = false;
        } else {
            // На мобильных устройствах меню закрыто
            toggleMenu(false);
        }
    };

    // Клик по кнопке
    burger.addEventListener('click', () => {
        if (desktopMedia.matches) return;

        const isOpen = burger.getAttribute('aria-expanded') === 'true';

        toggleMenu(!isOpen);
    });

    // Закрытие по Escape
    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape' &&
            burger.classList.contains('active')
        ) {
            toggleMenu(false);
            burger.focus();
        }
    });

    // Закрытие после перехода по ссылке
    nav.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            toggleMenu(false);
        }
    });

    // Смена мобильного и десктопного режимов
    desktopMedia.addEventListener('change', syncMenu);

    // Инициализация
    syncMenu();
});