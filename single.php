<?php
//Шаблон страницы одной записи
?>

<?php get_header(); ?>

<main class="post-single">

	<?php while( have_posts() ) : the_post();
		
		// Обрезаем короткое описание
		$description = wp_trim_words( get_the_excerpt(), 20, '...' );
	
	?>

		<article class="single-post">
			
			<!-- Hero-секция -->
			<header class="single-post__header">

				<!-- Фоновое изображение -->
				<?php if( has_post_thumbnail() ) : ?>
					<div class="single-post__thumbnail">
						<?php 
							the_post_thumbnail( 'full', array(
								'class' => 'single-post__image',
								'loading' => 'eager',
							) ); 
						?>
					</div>
				<?php endif; ?>

				<!-- Затемнение фона -->
				<div class="single-post__overlay"></div>

				<div class="container">
					<div class="single-post__header-content">

						<!-- Мета-данные -->
						<div class="single-post__meta">
							<?php get_template_part( 'template-parts/components/post-meta' ); ?>
						</div>
					
						<!-- Заголовок -->
						<h1 class="single-post__title">
							<?php the_title(); ?>
						</h1>

						<!-- Короткое описание -->
						<div class="single-post__description">
							<?php echo wp_kses_post( $description ); ?>
						</div>

					</div>
				</div>
			
			</header>

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