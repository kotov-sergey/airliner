<?php

/* Template Name: Каталог авиалайнеров */
$catalog_title = get_field( 'catalog_title' );
$catalog_description = get_field( 'catalog_description' );

// Модели
$airliner_count_obj = wp_count_posts( 'airliner');
$total_airliners = $airliner_count_obj ? $airliner_count_obj->publish : 0;

// Производители
$total_brands = wp_count_terms( [ 'taxonomy' => 'manufacturer', 'hide_empty' => false ] );
if ( is_wp_error( $total_brands ) ) $total_brands = 0;

// Типы фюзеляжа
$total_body_types = wp_count_terms( [ 'taxonomy' => 'body-type', 'hide_empty' => false ] );
if ( is_wp_error( $total_body_types ) ) $total_body_types = 0;

get_header();  
?>

<main class="site-main page-catalog">

	<?php while ( have_posts() ) : the_post(); ?>

        <!-- Hero-секция -->
		<section class="section page-catalog__hero">
			<div class="container">

				<div class="page-catalog__hero-inner">
					
                    <!-- Описание -->
					<div class="page-catalog__hero-text">
						<?php if ( $catalog_title ) : ?>
                            <h1 class="page-catalog__title">
                                <?php echo esc_html( $catalog_title ); ?>
                            </h1>
                        <?php endif; ?>
						
                        <?php if ( $catalog_description ) : ?>
                            <p class="page-catalog__description">
                                <?php echo esc_html( $catalog_description ); ?>
                            </p>
                        <?php endif; ?>
					</div>
				
					<div class="page-catalog__hero-stats">
				
						<div class="catalog-stat">
							<!-- Модели -->
                            <span class="catalog-stat__number"><?php echo esc_html( $total_airliners ); ?></span>
                            <span class="catalog-stat__label">Моделей</span>
						</div>
						
						<div class="catalog-stat">
							<!-- Производители -->
                            <span class="catalog-stat__number"><?php echo esc_html( $total_brands ); ?></span>
                            <span class="catalog-stat__label">Производителей</span>
						</div>

						<div class="catalog-stat">
							<!-- Тип -->
                            <span class="catalog-stat__number"><?php echo esc_html( $total_body_types ); ?></span>
                            <span class="catalog-stat__label">Типа фюзеляжа</span>
						</div>						
					</div>
				</div>

            </div>
		</section>
    <?php endwhile; ?>

    <!-- Основной каталог с фильтрами -->
    <section class="section page-catalog__content">
        <div class="container">
            <div class="page-catalog__layout">

                <!-- Сайдбар (фильтры) -->
                <aside class="page-catalog__sidebar">
                    <?php get_template_part( 'template-parts/components/catalog-filter' ); ?>
                </aside>

                <!-- Контейнер для результатов -->
                <div class="page-catalog__results" id="catalog-results">
                    <div class="l-grid l-grid--3 page-catalog__grid">

                        <?php
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                            $catalog_query = new WP_Query([
                                'post_type'=> 'airliner',
                                'posts_per_page'=> 9,
                                'paged' => $paged
                            ]);

                            if ( $catalog_query->have_posts() ) {
                                while ( $catalog_query->have_posts() ) {
                                    $catalog_query->the_post();
                                    get_template_part( 'template-parts/components/card-aircraft' );
                                }
                                wp_reset_postdata();
                            }
                            else {
                                echo '<p>Самолеты не найдены!</p>';
                            }
                        ?>

                    </div>

                    <!-- Пагинация -->
                    <?php if ( $catalog_query->max_num_pages > 1 ) : ?>
                        <div class="page-catalog__pagination">
                            <?php
                            echo paginate_links( array( 
                                'total' => $catalog_query->max_num_pages,
                                'current' => $paged,
                                'prev_text' => '&larr; Назад',
                                'next_text' => 'Вперёд &rarr;',
                            ) );
                            ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </section>

    <!-- Вывод кастомных секций -->
    <?php get_template_part( 'template-parts/builder' ); ?>
    
    <!-- SEO-текст каталога -->
    <div class="page-catalog__seo">
        <div class="container container--narrow">
            <div class="entry-content">
                <?php if ( get_the_content() ) : ?>
                    <?php the_content(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>