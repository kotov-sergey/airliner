<?php
// Шаблон страницы категории (category.php)

get_header();

$category = get_queried_object();
$category_description = category_description();
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
                    <span class="catalog-stat__number"><?php $category->count; ?>5</span>
                    <span class="catalog-stat__label">Статей в рубрике</span>
                </div>
        
            </div>

        </div>
    </section>

    <!-- Секция избранная статья -->
    <?php if ( have_posts() ) : the_post(); ?>
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
    <div class="category-tags">
        <div class="container">
                <?php get_template_part( 'template-parts/components/category-cloud' ); ?>
        </div>
    </div>

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
