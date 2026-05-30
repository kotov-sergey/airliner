document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('airliner-filter');
    const resultsContainer = document.getElementById('catalog-results');
    
    // Флаг для защиты от двойных кликов
    let isFetching = false;

    // Если формы или контейнера нет на странице — просто выходим
    if (!form || !resultsContainer) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault(); 

        if (isFetching) return; // Блокируем повторную отправку
        isFetching = true;

        // 1. Визуальный отклик (UX)
        resultsContainer.style.opacity = '0.5';
        resultsContainer.style.pointerEvents = 'none'; // Блокируем клики по старым карточкам

        // Меняем текст кнопки (ищем кнопку отправки внутри формы)
        const submitBtn = form.querySelector('button[type="submit"]');
        let originalBtnText = '';
        if (submitBtn) {
            originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Загрузка...';
        }

        // 2. Собираем данные
        const formData = new FormData(form);
        formData.append('action', 'filter_airliners'); 
        formData.append('security', airlinerAjax.nonce); 

        try {
            // 3. Отправляем запрос
            const response = await fetch(airlinerAjax.url, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const html = await response.text(); 
                
                // 4. Вставляем новые карточки
                resultsContainer.innerHTML = html;
            } else {
                console.error('Ошибка сервера при фильтрации');
                resultsContainer.innerHTML = '<p class="error-message">Произошла ошибка при загрузке данных. Попробуйте позже.</p>';
            }
        } catch (error) {
            console.error('Ошибка AJAX:', error);
            resultsContainer.innerHTML = '<p class="error-message">Нет связи с сервером. Проверьте интернет-соединение.</p>';
        } finally {
            // 5. Возвращаем всё в норму
            resultsContainer.style.opacity = '1';
            resultsContainer.style.pointerEvents = 'auto';
            if (submitBtn) {
                submitBtn.innerHTML = originalBtnText; // Возвращаем старый текст кнопки
            }
            isFetching = false;
        }
    });
});