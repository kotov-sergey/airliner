<?php
// Умный компонент навигации (лента)

$taxonomy = $args['taxonomy'] ?? 'category'; // Выбранная таксономия
$order = $args['order'] ?? 'DESC'; // Сортировка по убыванию
$orderby = $args['orderby'] ?? 'name'; // Сортировка по имени
$limit = $args['limit'] ?? '0'; // Лимит категорий

// Текст и ссылка для первой кнопки "Все"
$all_label = $args['all_label'] ?? ( $taxonomy === 'category' ? 'Все статьи' : 'Все' ); // Текст кнопки "Все"
$all_url = $args['all_url'] ?? ( $taxonomy === 'category' ? ( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ) : home_url( '/airliners/' ) ); // Ссылка кнопки "Все"

// Формирование запроса
$query_args = [
    'taxonomy' => $taxonomy,
    'parent' => 0,
    'order' => $order,
    'orderby' => $orderby,
    'hide_empty' => true
];

// Проверка на ограничение (лимит)
if ( $limit > 0 ) {
    $query_args['number'] = $limit;
}

// Получение категорий (терминов)
$categories = get_terms( $query_args );
if ( empty( $categories ) || is_wp_error( $categories ) ) return;

$current_cat_id = ( is_category() || is_tax( $taxonomy ) ) ? get_queried_object_id() : 0; // ID текущей категории
$is_all_active = is_home() || ( is_page( 'airliners' ) && empty( $_GET[ $taxonomy ] ) ); // Проверка (если это страница блога)
?>

<nav class="category-cloud" aria-label="Навигация по категориям">
    <ul class="category-cloud__list">

        <!-- Ссылка на все статьи блога / Категории -->
        <li class="category-cloud__item">
            <a 
                href="<?php echo esc_url( $all_url); ?>" 
                class="pill pill--subtle category-cloud__link <?php echo $is_all_active ? 'is-active' : ''; ?>"
            >
                <?php echo esc_html( $all_label ); ?>
            </a>
        </li>

        <!-- Список основных категорий -->
        <?php foreach( $categories as $category ) : ?>
            <?php
                $term_link = get_term_link( $category );
                if( is_wp_error( $term_link ) ) continue;

                $is_current = ( $current_cat_id === $category->term_id );
            ?>

            <li class="category-cloud__item">
                <a
                    href="<?php echo esc_url( $term_link); ?>"
                    class="pill pill--subtle category-cloud__link <?php echo $is_current ? 'is-active' : ''; ?>"
                >
                    <?php echo esc_html( $category->name ); ?>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</nav>