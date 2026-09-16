// Плавный скролл для hero-секции

document.addEventListener('DOMContentLoaded', () => {
    
    // Ищем кнопку внутри Hero
    const scrollBtn = document.querySelector('.hero__scroll-btn');
    
    // Если кнопки нет на странице (например, мы в "Контактах"), код не выполняется
    if (!scrollBtn) return; 

    scrollBtn.addEventListener('click', function() {
        // Получаем ID секции из атрибута data-target
        const targetId = this.getAttribute('data-target');
        
        if (targetId) {
            const targetSection = document.querySelector(targetId);

            if (targetSection) {
                targetSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});