document.addEventListener('DOMContentLoaded', function () {
  const burgerBtn = document.querySelector('.burger__btn');
  const navMenu = document.getElementById('navMenu');

  if (!burgerBtn || !navMenu) return;

  burgerBtn.addEventListener('click', () => {
    burgerBtn.classList.toggle('active');
    navMenu.classList.toggle('active');
  });

  // Закрытие меню при клике вне его области
  document.addEventListener('click', function (e) {
    if (!navMenu.contains(e.target) && !burgerBtn.contains(e.target)) {
      burgerBtn.classList.remove('active');
      navMenu.classList.remove('active');
    }
  });
});