document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('airliner-filter');
    const resultsContainer = document.getElementById('catalog-results');
    
    let isFetching = false;

    if (!form || !resultsContainer) return;

    // --- ГЛАВНАЯ ФУНКЦИЯ ОТПРАВКИ ЗАПРОСА ---
    // Вынесли логику сюда. По умолчанию грузим 1-ю страницу.
    async function fetchResults( page = 1 ) {
        if (isFetching) return;
        isFetching = true;

        // 1. Визуальный отклик
        resultsContainer.style.opacity = '0.5';
        resultsContainer.style.pointerEvents = 'none';

        const submitBtn = form.querySelector('button[type="submit"]');
        let originalBtnText = '';
        if (submitBtn) {
            originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Загрузка...';
        }

        // 2. Собираем данные формы
        const formData = new FormData(form);
        formData.append('action', 'filter_airliners'); 
        formData.append('security', airlinerAjax.nonce); 
        
        // ДОБАВЛЕНО: Передаем номер страницы на сервер!
        formData.append('paged', page); 

        try {
            // 3. Отправляем запрос
            const response = await fetch(airlinerAjax.url, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const html = await response.text(); 
                resultsContainer.innerHTML = html; // Обновляем сетку и пагинацию
                
                // Плавно скроллим вверх к результатам (UX улучшение)
                resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                console.error('Ошибка сервера при фильтрации');
                resultsContainer.innerHTML = '<p class="error-message">Произошла ошибка при загрузке данных. Попробуйте позже.</p>';
            }
        } catch (error) {
            console.error('Ошибка AJAX:', error);
            resultsContainer.innerHTML = '<p class="error-message">Нет связи с сервером. Проверьте интернет-соединение.</p>';
        } finally {
            // Возвращаем всё в норму
            resultsContainer.style.opacity = '1';
            resultsContainer.style.pointerEvents = 'auto';
            if (submitBtn) {
                submitBtn.innerHTML = originalBtnText;
            }
            isFetching = false;
        }
    }

    // --- СЛУШАТЕЛЬ 1: ОТПРАВКА ФОРМЫ ---
    form.addEventListener('submit', (e) => {
        e.preventDefault(); 
        // При новом поиске всегда начинаем с 1-й страницы
        fetchResults(1); 
    });

    // --- СЛУШАТЕЛЬ 2: КЛИК ПО ПАГИНАЦИИ ---
    // Вешаем слушатель на весь контейнер (Делегирование событий)
    resultsContainer.addEventListener('click', (e) => {
        
        // Проверяем, кликнули ли мы по ссылке (<a>) внутри пагинации
        const pageLink = e.target.closest('.catalog-content__pagination a.page-numbers');
        
        if (pageLink) {
            e.preventDefault(); // Запрещаем обычный переход по ссылке
            
            // Вытаскиваем номер страницы из URL ссылки WordPress (например, /page/2/)
            // Регулярное выражение ищет цифры после слова 'page'
            let pageNum = 1;
            const urlMatch = pageLink.href.match(/page\/(\d+)/); 
            
            if (urlMatch && urlMatch[1]) {
                pageNum = parseInt(urlMatch[1]);
            } else {
                // Обработка кнопки "Назад", если она ведет на 1-ю страницу (где нет /page/1/)
                pageNum = 1; 
            }

            // Вызываем нашу функцию с нужным номером страницы
            fetchResults(pageNum);
        }
    });

});