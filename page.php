<?php
/*
 * Общий шаблон страницы.
 * Выводит контент, а также кастомные секции если они есть.
 */
get_header();
?>

	<main class="main">

		<?php if ( have_posts() ) :
			while ( have_posts() ) : the_post();
				the_content();

					// Подключаем комментарии, если нужны
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

			endwhile;
					else :
						get_template_part( 'template-parts/builder' );
			endif; ?>
				
	</main>

<?php get_footer(); ?>