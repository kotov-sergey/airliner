<?php
// Умный компонент: навигация по блогу

$orderby = $args['orderby'] ?? 'name'; // Сортировка по имени
$order = $args['order'] ?? 'ASC'; // Сортировка по алфавиту
$limit = $args['limit'] ?? 0; // Лимит вывода
$taxonomy = $args['taxonomy'] ?? 'category'; // Сортировка по таксономии

if ( isset( $args['parent'] ) ) {
    $parent_id = $args['parent'];
}
elseif ( is_category() && empty( $args['orderby'] ) ) {
    $parent_id = get_queried_object_id();
}
else {
    $parent_id = 0;
}

$query_args = [
    'taxonomy' => $taxonomy,
    'orderby' => $orderby,
    'order' => $order,
    'hide_empty' => true
];

if ( $limit > 0 ) {
    $query_args['number'] = $limit;
}

if ( $parent_id !== '' && $parent_id !== false ) {
    $query_args['parent'] = $parent_id;
}

$categories = get_terms( $query_args );

if ( empty ( $categories ) || is_wp_error( $categories ) ) return;

$wrapper_class = $args['wrapper_class'] ?? '';
?>

<?php if ( $wrapper_class ) : ?>
    <div class="<?php echo esc_attr( $wrapper_class ); ?>">
        <div class="container">
<?php endif; ?>

    <nav class="category-cloud" aria-label="Навигация">
        <ul class="category-cloud__list">

            <?php foreach ( $categories as $category ) : ?>

                <li class="category-cloud__item">
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="pill pill--subtle category-cloud__link"><?php echo esc_html( $category->name ); ?></a>
                </li>
            
            <?php endforeach; ?>

        </ul>
    </nav>

<?php if ( $wrapper_class ) : ?>
    </div>
</div>
<?php endif; ?>