<?php 

	/*
	 * 1. Настройки темы (поддержка лого, меню)
	 */
	require get_template_directory() . '/inc/theme-setup.php';

	/*
	 * 2. Подключение стилей и скриптов (Vite / CSS / JS)
	 */
	require get_template_directory() . '/inc/enqueue-scripts.php';

	/*
	 * 3. Регистрация типов записей и таксономий
	 */
	require get_template_directory() . '/inc/post-types.php';	
	
	/*
	 * 4. Функции-помощники
	 */
	require get_template_directory() . '/inc/theme-functions.php';

	/*
	 * 5. Функции вывода на экран
	 */
	require get_template_directory() . '/inc/template-tags.php';	

	/*
	 * 6. Данные для авиалайнеров
	 */
	require get_template_directory() . '/inc/data-airliners.php';