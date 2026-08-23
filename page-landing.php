<?php
/*
Template Name: Лендинг
*/

get_header();
?>

<main class="site-main page-landing">

    <?php while ( have_posts() ) : the_post();
        $hero_background = get_field( 'hero_background' );
        $hero_title = get_field( 'hero_title' ) ?: get_the_title();
        $hero_description = get_field( 'hero_description' ); 
    ?>

        <!-- Hero-секция -->
        <?php if ( $hero_title ) : ?>

            <?php
                get_template_part( 'template-parts/components/hero', null, [
                    'title' => $hero_title,
                    'description' => $hero_description,
                    'background_image' => $hero_background,
                    'show_breadcrumbs' => true
                ] );
            ?>

        <?php endif; ?>

        <!-- Вывод кастомных блоков -->
        <?php get_template_part( 'template-parts/builder' ); ?>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>