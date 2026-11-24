<?php
// Шаблон страницы поиска

get_header();

// Получение глобального объекта запроса
global $wp_query;
$total_results = $wp_query->found_posts;

// Сортировка постов по массивам
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

    <!-- Hero-секция -->
	<?php 
		get_template_part( 'template-parts/components/pages/page-header', null, [
			'title' => 'Поиск: «' . get_search_query() . '»',
			'description' => 'Результаты поиска – найдено ' . $total_results . ' записей',
			'show_breadcrumbs' => false,
			'modifier' => 'page-header--center',
		] );
	?>

	<!-- Если постов не было найдено (фоллбек) -->
	<?php if ( $total_results === 0 ) : ?>

		<!-- Секция результат поиска -->
		<section class="section search-empty section--alt">
			<div class="container">

				<div class="search-empty__info">
					<div class="search-empty__image">
						<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-radar text-primary/40"><path d="M19.07 4.93A10 10 0 0 0 6.99 3.34"></path><path d="M4 6h.01"></path><path d="M2.29 9.62A10 10 0 1 0 21.31 8.35"></path><path d="M16.24 7.76A6 6 0 1 0 8.23 16.67"></path><path d="M12 18h.01"></path><path d="M17.99 11.66A6 6 0 0 1 15.77 16.67"></path><circle cx="12" cy="12" r="2"></circle><path d="m13.41 10.59 5.66-5.66"></path></svg>
					</div>
					<h2 class="search-empty__title">Ничего не найдено</h2>
					<p class="search-empty__description">Проверьте правильность написания или попробуйте более короткий запрос – например, «Boeing 737» вместо «Boeing 737-800 MAX».</p>
				</div>
			
			</div>
		</section>

		<!-- Секция фоллбэк (популярные авиалайнеры) -->
		<section class="section search-fallback-airliners">
			<div class="container">

				<!-- Заголовок секции популярных авиалайнеров -->
				<?php
					get_template_part( 'template-parts/components/ui/section-header', null, [
						'number' => '01',
						'data' => [
							'header_label' => 'Авиалайнеры',
							'header_title' => 'Популярные авиалайнеры',
							'header_description' => 'Посмотрите наши популярные авиалайнеры',
						]
					] );
				?>

				<!-- Сетка популярных авиалайнеров -->
				<div class="l-grid l-grid--4 search-fallback-airliners__grid">
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

								get_template_part( 'template-parts/components/cards/card-aircraft', null, [
									'layout' => 'vertical' 
								] );
							}
							wp_reset_postdata();
						}
					?>
				</div>

			</div>
		</section>

		<!-- Секция фоллбек (популярные рубрики) -->
		<section class="section section-fallback-posts">
			<div class="container">

				<!-- Заголовок секции популярные рубрики -->
				<?php
					get_template_part( 'template-parts/components/ui/section-header', null, [
						'number' => '02',
						'data' => [
							'header_label' => 'Рубрики',
							'header_title' => 'Популярные рубрики',
							'header_description' => 'Посмотрите наши популярные рубрики',
						]
					] );
				?>

				<!-- Компонент навигации (популярные рубрики) -->
				<?php get_template_part( 'template-parts/components/navigation/blog-nav' ); ?>
				
			</div>
		</section>
						
	<?php else : ?>

		<!-- Если были найдены посты -->

		<!--Навигационное меню (вкладки)-->
		<section class="search-tabs">

			<div class="container search-tabs__inner">
				<button class="pill pill--lg pill--solid search-tabs__button" type="button">Все результаты (<?php echo ( $total_results ); ?>)</button>
				<button class="pill pill--lg pill--solid search-tabs__button" type="button">Авиалайнеры (<?php echo count( $found_airliners); ?>)</button>
				<button class="pill pill--lg pill--solid search-tabs__button" type="button">Журнал (<?php echo count( $found_posts ); ?>)</button>
			</div>

		</section>

		<?php if ( have_posts() ) : ?>

			<!-- Найденные авиалайнеры -->
			<?php if ( ! empty( $found_airliners ) ) : ?>
				<section class="section search-airliners">
					<div class="container">

						<!-- Заголовок секции авиалайнеров -->
						<?php 
							get_template_part( 'template-parts/components/ui/section-header', null, [
								'number' => '01',
								'data' => [
									'header_label' => 'Модели',
									'header_title' => 'В каталоге авиалайнеров: ' . count( $found_airliners ),
									'header_description' => 'Посмотрите авиалайнеры, найденные по вашему запросу',
								]
							] );
						?>

						<!-- Сетка карточек найденных авиалайнеров -->
						<div class="l-grid l-grid--4 search-workspace__grid">
							<?php
								foreach ( $found_airliners as $post ) :
									setup_postdata( $post );
									get_template_part( 'template-parts/components/cards/card-aircraft' );
								endforeach;
								wp_reset_postdata();
							?>
						</div>

					</div>
				</section>
			<?php endif; ?>

			<!-- Найденные посты -->
			<?php if ( ! empty( $found_posts ) ) : ?>
				<section class="section search-posts">
					<div class="container">

						<!-- Заголовок секции постов -->
						<?php 
							get_template_part( 'template-parts/components/ui/section-header', null, [
								'number' => '02',
								'data' => [
									'header_label' => 'Посты',
									'header_title' => 'В каталоге постов: ' . count( $found_posts ),
									'header_description' => 'Посмотрите посты, найденные по вашему запросу',
								]
							] );
						?>

						<!-- Сетка карточек найденных постов -->
						<div class="l-grid l-grid--3 search-workspace__grid">
							<?php
								foreach ( $found_posts as $post ) :
									setup_postdata( $post );
									get_template_part( 'template-parts/components/cards/card-post' );
								endforeach;
								wp_reset_postdata();
							?>
						</div>

					</div>
				</section>
			<?php endif; ?>

		<?php endif; ?>
	
	<?php endif; ?>

    <!-- Секция призыва к действию -->
	<?php 
		get_template_part( 'template-parts/sections/shared/cta-block', null, [
			'section_title' => 'Не нашли нужный самолёт?',
			'section_description' => 'Откройте полный каталог авиалайнеров с умными фильтрами.',
			'section_button' => [
				'title' => 'Перейти в каталог',
				'url' => home_url( '/airliners/' ),
				'target' => '_self'
			],
			'section_image' => get_template_directory_uri() . '/public/images/cta-bg.webp',
			'section_modifier' => 'section--alt'
		] ); 
	?>

</main>

<?php get_footer(); ?>