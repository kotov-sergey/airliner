<?php
/* Template Name: Сравнение авиалайнеров */

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

// Ограничение максимального количества авиалайнеров
$plane_ids = array_slice( $plane_ids, 0, 4 );

// Подготовка данных сравнения
$compare_query = null;
$winners = [];

if ( count( $plane_ids ) >= 2 ) {

    // Запрос данных авиалайнеров
    $compare_query = new WP_Query( [
        'post_type'      => 'airliner',
        'post_status'    => 'publish',
        'post__in'       => $plane_ids,
        'orderby'        => 'post__in',
        'posts_per_page' => count( $plane_ids ),
    ] );

    // Проверка найденных авиалайнеров
    if ( $compare_query->found_posts >= 2 ) {

        // Получение ID найденных авиалайнеров
        $valid_plane_ids = wp_list_pluck( $compare_query->posts, 'ID' );

        // Данные лучших характеристик авиалайнеров
        $winners = get_airliner_compare_winners( $valid_plane_ids );
    }
}

get_header();
?>

<main class="site-main page-compare">

    <!-- Hero-секция -->
    <?php
        get_template_part( 'template-parts/components/pages/page-header', null, [
            'title'            => 'Сравнение авиалайнеров',
            'description'      => 'Сравнивайте характеристики и значения, выявляющие лучшие авиалайнеры в мире!',
            'show_breadcrumbs' => true,
        ] );
    ?>

    <section class="section page-compare__content">
        <div class="container">

            <?php if ( $compare_query && $compare_query->found_posts >= 2 ) : ?>

                <!-- Заголовок секции -->
                <?php
                    get_template_part( 'template-parts/components/ui/section-header', null, [
                        'data' => [
                            'header_title' => 'Результаты сравнения авиалайнеров',
                        ],
                    ] );
                ?>

                <!-- Таблица сравнения авиалайнеров -->
                <?php
                    get_template_part( 'template-parts/components/comparison/compare-table', null, [
                        'query'   => $compare_query,
                        'winners' => $winners,
                    ] );
                ?>

                <?php wp_reset_postdata(); ?>

            <?php elseif ( count( $plane_ids ) >= 2 ) : ?>

                <!-- Заглушка, если выбранные авиалайнеры не найдены -->
                <div class="empty-state">
                    <p class="empty-state__text">Выбранные авиалайнеры не найдены в базе данных.</p>
                    <a href="<?php echo esc_url( home_url( '/airliners/' ) ); ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>

            <?php else : ?>

                <!-- Заглушка, если выбрано меньше двух авиалайнеров -->
                <div class="empty-state">
                    <p class="empty-state__text">Для сравнения выберите как минимум 2 самолета из каталога.</p>
                    <a href="<?php echo esc_url( home_url( '/airliners/' ) ); ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>

            <?php endif; ?>

        </div>
    </section>

</main>

<?php get_footer(); ?>