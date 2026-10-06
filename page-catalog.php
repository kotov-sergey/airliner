<?php

/* Template Name: Каталог авиалайнеров */
$catalog_title = get_field( 'catalog_title' );
$catalog_description = get_field( 'catalog_description' );

get_header();  
?>

<main class="site-main page-catalog">

	<?php while ( have_posts() ) : the_post(); ?>

        <!-- Шапка страницы -->
        <?php
            get_template_part( 'template-parts/components/page-header', null, [
                'title' => $catalog_title,
                'description' => $catalog_description,
            ] );
        ?>

        <!-- Основной каталог с фильтрами -->
        <section class="section catalog-content page-catalog__content">
            <div class="container">

                <!-- Запрос на получение всех авиалайнеров -->
                <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $catalog_query = new WP_Query( [
                        'post_type' => 'airliner',
                        'posts_per_page' => 9,
                        'paged' => $paged
                    ] );

                    // Компонент каталога авиалайнеров
                    get_template_part( 'template-parts/sections/catalog-layout', null, [
                        'query' => $catalog_query
                    ] );
                ?>

            </div>
        </section>

        <!-- Вывод кастомных секций -->
        <?php get_template_part( 'template-parts/builder' ); ?>
    
        <!-- SEO-текст каталога -->
        <section class="section section--alt catalog-seo">
            <div class="container container--narrow">
                <div class="entry-content">
                    <?php if ( get_the_content() ) : ?>
                        <?php the_content(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    
    <?php endwhile; ?>

</main>

<?php get_footer(); ?>