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
            get_template_part( 'template-parts/components/pages/page-header', null, [
                'title' => $catalog_title,
                'description' => $catalog_description,
                'show_breadcrumbs' => true
            ] );
        ?>

        <!-- Компонент каталога авиалайнеров -->
        <?php
            get_template_part( 'template-parts/sections/catalog/catalog', null, 
                [
                    'query_args' => [
                        'post_type' => 'airliner',
                        'posts_per_page' => 9,
                        'post_status' => 'publish',
                    ],

                    'show_toolbar' => true,
                    'show_filters' => true,
                    'show_load_more' => true,

                    'card_view' => 'grid',
                ] 
            );
        ?>

        <!-- Вывод кастомных секций -->
        <?php get_template_part( 'template-parts/builder' ); ?>
    
    <?php endwhile; ?>

</main>

<?php get_footer(); ?>