<?php
/* Template Name: Избранные авиалайнеры */

// Получение ID авиалайнеров из адресной строки
$ids_param = isset( $_GET['ids'] )
    ? sanitize_text_field( wp_unslash( $_GET['ids'] ) )
    : '';

$plane_ids = ! empty( $ids_param )
    ? array_map( 'absint', explode( ',', $ids_param ) )
    : [];

// Удаление нулевых и повторяющихся ID
$plane_ids = array_filter( $plane_ids );
$plane_ids = array_unique( $plane_ids );
$plane_ids = array_values( $plane_ids );

// Ограничение максимального кол-ва авиалайнеров
$plane_ids = array_slice( $plane_ids, 0, 4 );

// Получение данных авиалайнеров
$favorites_query = null;

if ( ! empty( $plane_ids ) ) {
    $favorites_query = new WP_Query( [
        'post_type'      => 'airliner',
        'post_status'    => 'publish',
        'post__in'       => $plane_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count( $plane_ids ),
    ] );    
}

get_header(); 
?>

<main class="site-main page-favorites">

    <!-- Hero-секция -->
    <?php 
        get_template_part( 'template-parts/components/pages/page-header', null, [
            'title'  => 'Избранные авиалайнеры',
            'description' => 'Здесь собраны авиалайнеры, которые вы добавили в избранное.',
            'show_breadcrumbs' => true
            ] 
        );
    ?>

    <section class="section page-favorites__content">
        <div class="container">

            <!-- Сетка избранных авиалайнеров -->
            <?php if ( $favorites_query && $favorites_query->have_posts() ) : ?>

                <!-- Заголовок секции -->
                <?php 
                    get_template_part( 'template-parts/components/ui/section-header', null, [
                        'data' => [
                            'header_title' => 'Ваши избранные авиалайнеры',
                        ],
                    ]);
                ?>
            
                <div class="l-grid l-grid--4">
                    <?php while ( $favorites_query->have_posts() ) : ?>
                        <?php $favorites_query->the_post(); ?>

                            <?php get_template_part( 'template-parts/components/card-aircraft' ); ?>

                    <?php endwhile; ?>
                </div>
            
            <?php else : ?>

                <div class="empty-state">
                    <p class="empty-state__text"> Вы пока не добавили авиалайнеры в избранное.</p>
                    <a href="<?php echo esc_url( home_url( '/airliners/' ) ); ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>

            <?php endif; ?>

            <?php wp_reset_postdata(); ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>