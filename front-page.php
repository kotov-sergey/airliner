<?php
/*
 * Шаблон главной страницы.
 * Выводит секции на главную страницу.
 */
get_header();
?>

<main class="main">

	<?php get_template_part( 'template-parts/builder' ); ?>
	
</main>

<?php get_footer(); ?>