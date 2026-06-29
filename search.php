<?php
// Шаблон страницы поиска (search.php)

get_header();

// Получаем глобальный объект запроса
global $wp_query;
$total_results = $wp_query->found_posts;

// Сортируем посты по массивам
global $post;
$found_airliners = [];
$found_posts = [];

while ( have_posts() ) {
    the_post();
    if ( get_post_type() === 'airliner' ) { 
        $found_airliners[] = $post; 
    }
    elseif ( get_post_type() === 'post' ) { 
        $found_posts[] = $post; 
    }
}
wp_reset_postdata();
?>

<main class="site-main page-search">

    <!--Hero-секция -->
    <section class="section search-hero">

        <div class="search-hero__background">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/public/images/search-placeholder.webp' ); ?>" alt="Фон hero-секции страницы поиска" class="search-hero__image" />
        </div>

        <div class="container search-hero__inner">
            <p class="search-hero__results">Результаты поиска — найдено <?php echo ( $total_results ); ?></p>
			<h1 class="search-hero__title">Поиск: «<?php echo get_search_query(); ?>»</h1>
            <input id="search-input" class="search-hero__input" type="search" name="s" placeholder="Введите название самолёта, бренда или темы..." />
        </div>

    </section>

	<?php if ( $total_results === 0 ) : ?>

		<!-- Секция результат поиска -->
		<section class="section search-empty">
			<div class="container search-empty__inner">
				<h2 class="search-empty__title">Ничего не найдено</h2>
				<p class="text-muted">Проверьте правильность написания или попробуйте более короткий запрос — например, «Boeing 737» вместо «Boeing 737-800 MAX».</p>
			</div>
		</section>

		<!-- Секция популярные авиалайнеры -->
		<section class="section section-fallback">
			<div class="container">
				<h2 class="section-fallback__title">Популярные авиалайнеры</h2>

				<div class="l-grid l-grid--4">
					<?php
					
						$fallback_args = [
							'post_type' => 'airliner',
							'posts_per_page' => '4',
							'orderby' => 'comment_count',
							'ignore_sticky_posts' => 1
						];

						$fallback_query = new WP_Query( $fallback_args );

						if ( $fallback_query->have_posts() ) {
							
							while ( $fallback_query->have_posts() ) {
								$fallback_query->the_post();

								get_template_part( 'template-parts/components/card-aircraft', null, [
									'layout' => 'vertical' 
								] );
							}
							wp_reset_postdata();
						}
					?>
				</div>
			
			</div>
		</section>

	<?php else : ?>

		<!--Навигационное меню (вкладки)-->
		<section class="search-tabs">

			<div class="container search-tabs__inner">
				<button class="airliner-pill" type="button">Все результаты (<?php echo ( $total_results ); ?>)</button>
				<button class="airliner-pill" type="button">Авиалайнеры (<?php echo count( $found_airliners); ?>)</button>
				<button class="airliner-pill" type="button">Журнал (<?php echo count( $found_posts ); ?>)</button>
			</div>

		</section>

		<!-- Найденные авиалайнеры и посты -->
		<?php if ( have_posts() ) : ?>
			<section class="section search-workspace">
				<div class="container">

					<div class="search-workspace__layout">
						<?php if ( ! empty( $found_airliners ) ) : ?>
							<div class="search-workspace__group">
								<div class="search-workspace__header">
									<div class="search-workspace__icon"><?php echo airliner_get_svg( 'specs/run-up' ); ?></div>
									<h2 class="search-workspace__title">В каталоге лайнеров: <?php echo count( $found_airliners); ?> моделей</h2>
								</div>

								<!-- Сетка карточек найденных авиалайнеров -->
								<div class="l-grid l-grid--4 search-workspace__grid">
									<?php
										foreach ( $found_airliners as $post ) :
											setup_postdata( $post );
											get_template_part( 'template-parts/components/card-aircraft' );
										endforeach;
										wp_reset_postdata();
									?>
								</div>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $found_posts ) ) : ?>
							<div class="search-workspace__group">
								<div class="search-workspace__header">
									<div class="search-workspace__icon"><?php echo airliner_get_svg( 'specs/thrust' ); ?></div>
									<h2 class="search-workspace__title">В бортовом журнале: <?php echo count( $found_posts ); ?> статей</h2>
								</div>

								<!-- Сетка карточек найденных постов -->
								<div class="l-grid l-grid--3 search-workspace__grid">
									<?php
										foreach ( $found_posts as $post ) :
											setup_postdata( $post );
											get_template_part( 'template-parts/components/card-post' );
										endforeach;
										wp_reset_postdata();
									?>
								</div>
							</div>
						<?php endif; ?>
					</div>

				</div>
			</section>
		<?php endif; ?>

		<!--
		<section class="section search-pagination">
			<div class="container">
				<?php the_posts_pagination(['prev_text' => '←', 'next_text' => '→']); ?>
			</div>
		</section>
		-->
	
	<?php endif; ?>

    <!--Секция призыва к действию-->
	<?php 
		get_template_part( 'template-parts/sections/section-cta-simple', null, [
			'section_title' => 'Не нашли нужный самолёт?',
			'section_description' => 'Откройте полный каталог авиалайнеров с умными фильтрами.',
			'section_button' => [
				'title' => 'Перейти в каталог',
				'url' => home_url( '/catalog/' ),
				'target' => '_self'
			]
		] ); 
	?>

</main>

<?php get_footer(); ?>