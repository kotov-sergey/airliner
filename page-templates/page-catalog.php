<?php

/* Template Name: Каталог авиалайнеров */
$catalog_title = get_field( 'catalog_title' ) ?: get_the_title();
$catalog_description = get_field( 'catalog_description' ) ?: '';

get_header();  
?>

<main class="site-main page-catalog">

	<?php while ( have_posts() ) : the_post(); ?>

        <!-- Шапка страницы -->
        <?php
            get_template_part( 'template-parts/components/page-header', null, [
                'title' => $catalog_title,
                'description' => $catalog_description,
                'show_breadcrumbs' => true
            ] );
        ?>
        
        <!-- Компонент быстрых фильтров (Бренды + Типы фюзеляжа) -->
        <?php get_template_part( 'template-parts/components/catalog-pills' ); ?>

        <!-- Компонент панели поиска и сортировки каталога -->
        <?php get_template_part( 'template-parts/components/catalog-toolbar' ); ?>

        <!-- Основной каталог с фильтрами -->
        <section class="section section-catalog container page-catalog__container">
            <div class="catalog-layout">

                <!-- Сайдбар с фильтрами -->
                <aside class="catalog-sidebar">
                    <?php get_template_part( 'template-parts/components/catalog-filter' ); ?>
                </aside>

                <!-- Сетка вывода авиалайнеров -->
                <div class="catalog-content">
                    <div id="airliner-results" class="catalog-grid">
                        
                        <?php
                            $airliners_query = new WP_Query( [
                                'post_type' => 'airliner',
                                'posts_per_page' => 9,
                                'post_status' => 'publish'
                            ] );

                            if ( $airliners_query->have_posts() ) :
                                while ( $airliners_query->have_posts() ) : $airliners_query->the_post();
                                    get_template_part( 'template-parts/components/card-aircraft', null, [
                                        'view' => 'grid'
                                    ] );
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<p>Лайнеры пока не добавлены.</p>';
                            endif;
                        ?>

                    </div>
                    
                    <!-- Кнопка "Показать еще" -->
                    <?php if ( $airliners_query->max_num_pages > 1 ) : ?>
                        <div class="catalog-load-more" id="load-more-wrapper">
                            <button
                                type="button"
                                id="load-more-btn"
                                class="btn btn--secondary load-more-btn"
                                data-page="1"
                                 data-max-pages="<?php echo esc_attr( $airliners_query->max_num_pages ); ?>"
                            >
                                <span class="load-more-btn__text">Показать ещё</span>
                                <span class="load-more-btn__loader" style="display: none;">Загрузка...</span>
                            </button>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </section>

        <!-- Вывод кастомных секций -->
        <?php get_template_part( 'template-parts/builder' ); ?>
    
    <?php endwhile; ?>

</main>

<?php get_footer(); ?>