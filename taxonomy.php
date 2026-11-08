<?php
// Общий шаблон таксономии

$current_term = get_queried_object();

$hero_title = $current_term->name;
$hero_description = $current_term->description;

$seo_text = get_field( 'seo_text', $current_term );

get_header();
?>

<main class="site-main page-taxonomy">

    <!-- Hero-секция таксономии -->
    <?php
        get_template_part( 'template-parts/components/pages/page-header', null, [
            'title' => $hero_title,
            'description' => $hero_description,
            'modifier' => 'page-header--large',
            'show_breadcrumbs' => true
        ] );
    ?>

    <!-- Компонент каталога авиалайнеров -->
    <?php
        global $wp_query;

        get_template_part( 'template-parts/sections/catalog/catalog', null, 
            [
                'query' => $wp_query,

                'show_toolbar' => true,
                'show_filters' => true,
                'show_load_more' => true,

                'card_view' => 'grid',
                'empty_message' => 'В этой категории пока нет авиалайнеров.',
            ]
        );
    ?>

    <!-- Секция связанные статьи таксономии -->
    <?php 
        get_template_part( 'template-parts/post/post-related-by-term', null, [
            'term' =>$current_term
        ] );
    ?>

    <!-- Секция SEO-текст таксономии -->
    <?php if ( $seo_text ) : ?>
        <section class="section page-taxonomy__seo">
            <div class="container container--narrow">
                <div class="entry-content">
                    <?php echo wp_kses_post( $seo_text ); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
