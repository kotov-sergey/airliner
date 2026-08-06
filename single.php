<?php
//Шаблон страницы одной записи
?>

<?php get_header(); ?>

<main class="post-single">

	<?php while( have_posts() ) : the_post();
		
		// Данные для Hero-секции
		$hero_background = get_post_thumbnail_id();
		$hero_title = get_the_title();
		$hero_description = wp_trim_words( get_the_excerpt(), 12, '...' );
	?>

		<article class="single-post">
			
			<!-- Hero-секция -->
			<?php
				get_template_part( 'template-parts/components/hero', null, [
					'title' => $hero_title ? $hero_title : get_the_title(),
					'description' => $hero_description,
					'background_image' => $hero_background,
					'show_meta' => true
				] );
			?>

			<!-- Основное содержимое -->
			<div class="container container--narrow">
				<div class="single-post__content entry-content">
					<?php the_content(); ?>
				</div>
			</div>

		</article>

		<!-- Секция Похожие статьи (рекомендуемые) -->
		<?php get_template_part( 'template-parts/post/post', 'related' ); ?>

		<!-- CTA-секция -->
		<?php get_template_part( 'template-parts/sections/cta-simple', null, [
				'section_title' => 'Подпишитесь на нашу рассылку',
				'section_description' => 'Получайте свежие статьи о мире авиации, обзоры новых самолетов и эксклюзивные материалы прямо на вашу почту.',
				'section_button' => [
					'title' => 'Перейти в каталог',
					'url' => home_url( '/catalog/' ),
					'target' => '_self'
				]
			] );
		?>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>