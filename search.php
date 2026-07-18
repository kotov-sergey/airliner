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
            <p class="search-hero__results">Результаты поиска – найдено <?php echo ( $total_results ); ?></p>
			<h1 class="search-hero__title">Поиск: «<?php echo get_search_query(); ?>»</h1>
            <input id="search-input" class="search-hero__input" type="search" name="s" placeholder="Введите название самолёта, бренда или темы..." />
        </div>

    </section>

	<?php if ( $total_results === 0 ) : ?>

		<!-- Секция результат поиска -->
		<section class="section search-empty section--white">
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

		<!-- Секция фоллбэк ( популярные лайнеры и рубрики ) -->
		<section class="section search-fallback">
			<div class="container">

				<div class="search-fallback__layout">
					
					<div class="search-fallback__group">
						<h2 class="search-fallback__title">Популярные авиалайнеры</h2>

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

					<div class="search-fallback__group">
						<h2 class="search-fallback__title">Популярные рубрики</h2>

						<div class="search-fallback__actions">
							<?php get_template_part( 'template-parts/components/category-cloud', null, [
								'orderby' => 'count',
								'order' => 'DESC',
								'limit' => 6
							] ); ?>
						</div>
					</div>

				</div>
			
			</div>
		</section>

	<?php else : ?>

		<!--Навигационное меню (вкладки)-->
		<section class="search-tabs">

			<div class="container search-tabs__inner">
				<button class="pill pill--lg pill--subtle search-tabs__button" type="button">Все результаты (<?php echo ( $total_results ); ?>)</button>
				<button class="pill pill--lg pill--subtle search-tabs__button" type="button">Авиалайнеры (<?php echo count( $found_airliners); ?>)</button>
				<button class="pill pill--lg pill--subtle search-tabs__button" type="button">Журнал (<?php echo count( $found_posts ); ?>)</button>
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
		get_template_part( 'template-parts/sections/cta-simple', null, [
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