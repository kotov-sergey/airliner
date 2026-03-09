// Инициализация галереи Glighbox
import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.min.css';

document.addEventListener('DOMContentLoaded', () => {
    // Инициализируем галерею
    // Она будет искать все ссылки с классом .glightbox
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true, // Зациклить листание
        zoomable: true, // Разрешить зум картинки

        // Настройки подписей
        descPosition: 'bottom', // Подпись снизу
    });
});