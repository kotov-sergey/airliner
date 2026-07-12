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
        <?php if ( $hero_title && $hero_description ) : ?>
            <section class="landing-hero">

                <div class="landing-hero__background">
                    <?php if ( $hero_background ) : ?>
                        <?php echo wp_get_attachment_image( $hero_background, 'full', false, ['class' => 'landing-hero__image'] ); ?>
                    <?php endif; ?>

                    <div class="landing-hero__overlay"></div>
                </div>

                <div class="container landing-hero__container">
                    <div class="landing-hero__content">
                        <h1 class="landing-hero__title"><?php echo esc_html( $hero_title ); ?></h1>

                        <?php if ( $hero_description ) : ?>
                            <p class="landing-hero__description"><?php echo esc_html( $hero_description ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

            </section>
        <?php endif; ?>

        <!-- Вывод кастомных блоков -->
        <?php get_template_part( 'template-parts/builder' ); ?>

    <?php endwhile; ?>

</main>

<?php get_footer(); ?>