<?php
/*
Template Name: Конструктор блоков
*/

get_header();
?>

<main class="site-main page-landing">

    <?php while ( have_posts() ) : the_post();
        $hero_background = get_field( 'hero_background' );
        $hero_title = get_field( 'hero_title' ) ?: get_the_title();
        $hero_description = get_field( 'hero_description' ); 
    ?>

        <!-- Шапка страницы -->
            <?php
                get_template_part( 'template-parts/components/page-header', null, [
                    'title' => $hero_title,
                    'description' => $hero_description,
                    'bg_image' => $hero_background,
                    'show_breadcrumbs' => true
                ] );
            ?>

        <!-- Вывод кастомных блоков -->
        <?php get_template_part( 'template-parts/builder' ); ?>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>