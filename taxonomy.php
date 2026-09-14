<?php
// Общий шаблон таксономии

$current_term = get_queried_object();

$brand_image = get_field( 'brand_logo', $current_term );

$hero_background = get_field( 'taxonomy_hero_bg', $current_term );
$hero_title = $current_term->name;
$hero_description = $current_term->description;

$seo_text = get_field( 'seo_text', $current_term );

get_header();
?>

<main class="site-main page-taxonomy">

    <!-- Hero-секция таксономии -->
    <?php
        get_template_part( 'template-parts/components/hero', null, [
            'title' => $hero_title ? $hero_title : get_the_title(),
            'description' => $hero_description,
            'background_image' => $hero_background,
            'show_breadcrumbs' => true
        ] );
    ?>

    <!-- Секция каталог таксономии -->
    <section class="section catalog-content page-taxonomy__content">
        <div class="container">

            <!-- Компонент каталога авиалайнеров -->
            <?php get_template_part( 'template-parts/sections/catalog-layout'); ?>

        </div>
    </section>

    <!-- Секция связанные статьи таксономии -->
    <?php 
        get_template_part( 'template-parts/post/post-related-by-term', null, [
            'term' =>$current_term
        ] );
    ?>

    <!-- Секция SEO-текст таксономии -->
    <?php if ( $seo_text ) : ?>
        <section class="section taxonomy-seo">
            <div class="container container--narrow">
                <div class="entry-content">
                    <?php echo wp_kses_post( $seo_text ); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
