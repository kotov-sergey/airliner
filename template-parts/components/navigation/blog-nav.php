<?php
// Компонент навигации по категориям блога

// Получение всех категорий блога
$blog_categories = get_categories( [
    'orderby' => 'name',
    'order' => 'ASC',
    'parent' => 0,
    'hide_empty' => true
] );

// Страница записей блога
$posts_page_id = (int) get_option( 'page_for_posts' );
$all_categories_url = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );

// Текущая категория
$current_category_id = is_category() ? (int) get_queried_object_id() : 0;

// Активна ли ссылка "Все категории"
$is_all_categories_active = is_home();
?>

<nav class="category-cloud" aria-label="Категории блога">
    <ul class="category-cloud__list">

        <!-- Кнопка "Все категории" -->
        <li class="category-cloud__item">
            <a 
                href="<?php echo esc_url( $all_categories_url ); ?>" 
                class="pill pill--solid category-cloud__link <?php echo $is_all_categories_active ? 'is-active' : ''; ?>"
                <?php echo $is_all_categories_active ? 'aria-current="page"' : ''; ?>
            >
                Все категории
            </a>
        </li>

        <!-- Список всех категорий блога -->
        <?php foreach ( $blog_categories as $category ) : ?>
            <?php
                // Ссылка категории
                $category_url = get_category_link( $category->term_id );

                // Активна ли категория или одна из её дочерних категорий
                $is_active = $current_category_id === (int) $category->term_id
                    || (
                        $current_category_id
                        && cat_is_ancestor_of( $category->term_id, $current_category_id )
                    );
            ?>

            <!-- Отдельная категория блога -->
            <li class="category-cloud__item">
                <a
                    href="<?php echo esc_url( $category_url ); ?>"
                    class="pill pill--solid category-cloud__link <?php echo $is_active ? 'is-active' : ''; ?>"
                    <?php echo $is_active ? 'aria-current="page"' : ''; ?>
                >
                    <?php echo esc_html( $category->name ); ?>
                </a>
            </li>
        <?php endforeach; ?>

    </ul>
</nav>