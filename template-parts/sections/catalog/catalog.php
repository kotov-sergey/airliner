<?php
/**
 * Секция каталога авиалайнеров.
 *
 * Принимает существующий WP_Query или создаёт собственный.
 *
 * Доступные аргументы:
 * - query: существующий объект WP_Query.
 * - query_args: аргументы собственного WP_Query.
 * - show_toolbar: показывать панель инструментов.
 * - show_filters: показывать боковую панель фильтров.
 * - show_load_more: показывать кнопку загрузки.
 * - card_view: вариант отображения карточки.
 */

$args = wp_parse_args(
    $args ?? [],
    [
        'query'                  => null,
        'query_args'             => [],
        'show_toolbar'           => true,
        'show_filters'           => true,
        'show_load_more'         => true,
        'card_view'              => 'grid',
        'results_id'             => 'airliner-results',
        'load_more_wrapper_id'   => 'load-more-wrapper',
        'load_more_button_id'    => 'load-more-btn',
        'empty_message'          => 'Лайнеры пока не добавлены.',
    ]
);

// Получаем переданный запрос или создаём собственный.
$catalog_query = $args['query'];

if ( ! ( $catalog_query instanceof WP_Query ) ) {
    $query_args = wp_parse_args(
        $args['query_args'],
        [
            'post_type'      => 'airliner',
            'posts_per_page' => 9,
            'post_status'    => 'publish',
        ]
    );

    $catalog_query = new WP_Query( $query_args );
}

// Проверяем, используется ли основной запрос WordPress.
$is_main_query = (
    isset( $GLOBALS['wp_query'] )
    && $catalog_query === $GLOBALS['wp_query']
);

$current_page = max(
    1,
    (int) $catalog_query->get( 'paged' )
);

$layout_class = $args['show_filters']
    ? 'catalog-layout'
    : 'catalog-layout catalog-layout--no-sidebar';
?>

<section class="section section-wrapper catalog-section">

    <div class="container">

        <?php if ( $args['show_toolbar'] ) : ?>

            <div class="catalog-section__toolbar">
                <?php
                    get_template_part(
                        'template-parts/components/catalog/catalog-toolbar',
                        null,
                        [
                            'total_count'  => $catalog_query->found_posts,
                            'current_sort' => $args['current_sort'] ?? 'popular',
                            'current_view' => $args['current_view'] ?? 'grid',
                        ]
                    );
                ?>
            </div>

        <?php endif; ?>

        <div class="<?php echo esc_attr( $layout_class ); ?>">

            <?php if ( $args['show_filters'] ) : ?>

                <aside class="catalog-layout__sidebar catalog-sidebar" aria-label="Фильтры каталога">

                    <?php
                        get_template_part(
                            'template-parts/components/catalog/catalog-filter'
                        );
                    ?>

                </aside>

            <?php endif; ?>

            <div class="catalog-layout__content catalog-content">

                <div
                    id="<?php echo esc_attr( $args['results_id'] ); ?>"
                    class="catalog-layout__grid catalog-grid"
                >

                    <?php if ( $catalog_query->have_posts() ) : ?>

                        <?php while ( $catalog_query->have_posts() ) : ?>

                            <?php $catalog_query->the_post(); ?>

                            <?php
                                get_template_part(
                                    'template-parts/components/cards/card-aircraft',
                                    null,
                                    [
                                        'view' => $args['card_view'],
                                    ]
                                );
                            ?>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <p class="catalog-layout__empty">
                            <?php echo esc_html( $args['empty_message'] ); ?>
                        </p>

                    <?php endif; ?>

                </div>

                <?php
                    // Восстанавливаем глобальный объект записи,
                    // только если использовали отдельный запрос.
                    if ( ! $is_main_query ) {
                        wp_reset_postdata();
                    }
                ?>

                <?php if (
                    $args['show_load_more']
                    && $catalog_query->max_num_pages > $current_page
                ) : ?>

                    <div
                        class="catalog-layout__load-more catalog-load-more"
                        id="<?php echo esc_attr( $args['load_more_wrapper_id'] ); ?>"
                    >

                        <button
                            type="button"
                            id="<?php echo esc_attr( $args['load_more_button_id'] ); ?>"
                            class="btn btn--secondary load-more-btn"
                            data-page="<?php echo esc_attr( $current_page ); ?>"
                            data-max-pages="<?php echo esc_attr( $catalog_query->max_num_pages ); ?>"
                        >

                            <span class="load-more-btn__text">
                                Показать ещё
                            </span>

                            <span
                                class="load-more-btn__loader"
                                style="display: none;"
                            >
                                Загрузка...
                            </span>

                        </button>

                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</section>