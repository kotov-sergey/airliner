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
			<img src="<?php echo esc_url( get_template_directory_uri() . '/public/images/404-placeholder.png' ); ?>" alt="Фон hero-секции страницы поиска" class="search-hero__image" />
        </div>

        <div class="container search-hero__inner">
            <p class="search-hero__results">Результаты поиска — найдено <?php echo ( $total_results ); ?></p>
			<h1 class="search-hero__title">Поиск: «<?php echo get_search_query(); ?>»</h1>
            <input id="search-input" class="search-hero__input" type="search" name="s" placeholder="Введите название самолёта, бренда или темы..." />
        </div>

    </section>

	<?php if ( $total_results === 0 ) : ?>

		<section class="section search-empty">
			<div class="container">
				<p class="text-muted">Извините. По вашему запросу не было найдено результатов.</p>
			</div>
		</section>
	
	<?php else : ?>

		<!--Навигационное меню (вкладки)-->
		<section class="search-tabs">

			<div class="container search-tabs__inner">
				<button class="airliner-pill" type="button">Все результаты<?php echo ( $total_results ); ?></button>
				<button class="airliner-pill" type="button">Авиалайнеры <?php echo count( $found_airliners); ?></button>
				<button class="airliner-pill" type="button">Журнал <?php echo count( $found_posts ); ?></button>
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
									<h2 class="search-workspace__title">В каталоге лайнеров: <?php echo count( $found_airliners); ?></h2>
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
									<h2 class="search-workspace__title">В бортовом журнале: <?php echo count( $found_posts ); ?></h2>
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