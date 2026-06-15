<?php
// Шаблон таксономии Производителей (Брендов)

$current_term = get_queried_object();

$brand_image = get_field( 'brand_logo', $current_term );

get_header();
?>

<main class="site-main taxonomy-brand">
    <div class="container">

        <section class="section taxonomy-brand__hero">
            
            <div class="taxonomy-brand__media">
                <img src="<?php echo esc_html( $brand_image['url'] ); ?>" class="taxonomy-brand__image" alt="" />
                <h1 class="taxonomy-brand__title"><?php echo esc_html( $current_term->name ); ?></h1>
            </div>

            <p class="taxonomy-brand__description"><?php echo esc_html( $current_term->description ); ?></p>
        
        </section>



    </div>
</main>

<?php get_footer(); ?>
