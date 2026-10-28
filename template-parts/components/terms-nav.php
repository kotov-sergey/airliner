<?php
// Умный компонент навигации (лента таблеток)

$taxonomy    = $args['taxonomy'] ?? 'category';
$filter_name = $args['filter_name'] ?? 'brand';
$order       = $args['order'] ?? 'ASC';
$orderby     = $args['orderby'] ?? 'name';
$limit       = $args['limit'] ?? 0;
$all_label   = $args['all_label'] ?? 'Все';

// Получение терминов
$query_args = [
    'taxonomy'   => $taxonomy,
    'parent'     => 0,
    'order'      => $order,
    'orderby'    => $orderby,
    'hide_empty' => true
];

if ( $limit > 0 ) {
    $query_args['number'] = $limit;
}

$categories = get_terms( $query_args );
if ( empty( $categories ) || is_wp_error( $categories ) ) return;

// Получаем выбранные ID из GET-параметров URL для подсветки активных
$current_selected = [];
if ( ! empty( $_GET[ $filter_name ] ) ) {
    $current_selected = explode( ',', sanitize_text_field( $_GET[ $filter_name ] ) );
}

$is_all_active = empty( $current_selected );
?>

<nav class="category-cloud" aria-label="Быстрый фильтр">
    <ul class="category-cloud__list">

        <!-- Кнопка "Все" -->
        <li class="category-cloud__item">
            <a 
                href="<?php echo esc_url( remove_query_arg( $filter_name ) ); ?>" 
                class="pill pill--subtle category-cloud__link <?php echo $is_all_active ? 'is-active' : ''; ?>"
                data-pill-all="<?php echo esc_attr( $filter_name ); ?>"
            >
                <?php echo esc_html( $all_label ); ?>
            </a>
        </li>

        <!-- Список терминов (Бренды / Типы) -->
        <?php foreach ( $categories as $category ) : ?>
            <?php
                // Проверяем активность таблетки
                $is_current = in_array( (string) $category->term_id, $current_selected, true );
                
                // SEO-ссылка
                $term_url = add_query_arg( $filter_name, $category->term_id );
            ?>

            <li class="category-cloud__item">
                <a
                    href="<?php echo esc_url( $term_url ); ?>"
                    class="pill pill--subtle category-cloud__link <?php echo $is_current ? 'is-active' : ''; ?>"
                    data-pill-item
                    data-pill-group="<?php echo esc_attr( $filter_name ); ?>"
                    data-term-id="<?php echo esc_attr( $category->term_id ); ?>"
                >
                    <?php echo esc_html( $category->name ); ?>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</nav>