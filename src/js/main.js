// Импорт стилей (Vite сам выделит их в отдельный CSS файл при билде)
import '../scss/main.scss';

import './modules/burger-menu.js'; // Скрипт бургер-меню
import './modules/scroll-button.js'; // Скрипт кнопки hero-секции
import './modules/gallery.js'; // Скрипт подключения галереи Glightbox
import './modules/catalog-filter.js'; // Скрипт для подключения AJAX-фильтров
import './modules/catalog-filter-accordion.js'; // Скрипт аккардеона блока фильтрации

import {initCatalogView } from './modules/catalog-view.js' // Скрипт панели поиска и сортировки каталога

import { initCompare } from './modules/compare-table.js'; // Скрипт обработки и вывода выбранных авиалайнеров

document.addEventListener('DOMContentLoaded', () => {
    initCompare();
    initCatalogView();
});
