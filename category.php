<?php
// Шаблон страницы категории (category.php)

get_header();

$category = get_queried_object();
$category_description = category_description();

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>

<main class="site-main page-category">

    <!-- Hero-секция категории -->
    <section class="section category-hero">
        <div class="container">

            <div class="category-hero__layout">
            
                <div class="category-hero__content">
                    <h1 class="category-hero__title"><?php single_cat_title(); ?></h1>

                    <?php if ( $category_description ) : ?>
                        <div class="category-hero__description">
                            <?php echo wp_kses_post( wpautop( $category_description ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="catalog-stat">
                    <span class="catalog-stat__number"><?php echo esc_html( $category->count ); ?></span>
                    <span class="catalog-stat__label">Статей в рубрике</span>
                </div>
        
            </div>

        </div>
    </section>

    <!-- Секция избранная статья -->
    <?php if ( $paged === 1 && have_posts() ) : the_post(); ?>
        <section class="section category-featured">
            <div class="container">

                <h2 class="category-featured__title">Выбор редакции</h2>

                <?php 
                    get_template_part( 'template-parts/components/card-post', null, [
                        'layout' => 'featured'
                    ]);
                ?>

            </div>
        </section>
    <?php endif; ?>

    <!-- Навигация подкатегорий основной категории -->
    <?php 
        get_template_part( 'template-parts/components/category-cloud', null, [
            'wrapper_class' => 'page-category__tags'
        ] );
     ?>

    <!-- Секция все статьи рубрики -->
    <?php if ( have_posts() ) : ?>
        <section class="section category-archive">
            <div class="container">

                <h2 class="category-archive__title">Все статьи рубрики</h2>

                <div class="l-grid l-grid--3">
                    <?php 
                        while ( have_posts() ) : the_post();
                            get_template_part( 'template-parts/components/card-post');
                        endwhile;
                    ?>
                </div>

                <!-- Пагинация архива статей -->
                <div class="page-category__pagination">
                    <?php the_posts_pagination(['prev_text' => '←', 'next_text' => '→']); ?>
                </div>

            </div>
        </section>

    <?php else : ?>

        <!-- Если в категории нет постов -->
        <section class="section category-empty">
            <div class="container">
                <p class="text-muted">В этой рубрике пока нет записей.</p>
            </div>
        </section>

    <?php endif; ?>

    <!-- Секция CTA категории -->
    <?php
        get_template_part( 'template-parts/builder', null, [
            'page_id' => $category
        ] ); 
    ?>

</main>

<?php get_footer(); ?>