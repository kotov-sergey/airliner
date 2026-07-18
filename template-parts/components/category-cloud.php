<?php
// Умный компонент: навигация по блогу

$parent_id = 0;

if ( is_category() ) {
    $parent_id = get_queried_object_id();
}

$categories = get_categories([
    'taxonomy' => 'category',
    'parent' => $parent_id,
    'hide_empty' => true
]);

if ( empty ( $categories ) || is_wp_error( $categories ) ) return;
?>

<nav class="category-cloud" aria-label="Навигация по блогу">
    <ul class="category-cloud__list">

        <?php foreach ( $categories as $category ) : ?>

            <li class="category-cloud__item">
                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="pill pill--subtle category-cloud__link"><?php echo esc_html( $category->name ); ?></a>
            </li>
        
        <?php endforeach; ?>

    </ul>
</nav>