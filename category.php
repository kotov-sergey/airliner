<?php
// Шаблон страницы категории (category.php)

get_header();

$category = get_queried_object();

$category_title = $category->name;
$category_description = category_description();
$category_count = $category->count;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>

<main class="site-main page-category">

    <!-- Шапка страницы -->
    <?php 
        get_template_part( 'template-parts/components/page-header', null, [
            'title' => $category_title,
            'description' => $category_description,
            'stats' => [
                [
                    'number' => $category_count,
                    'label' => 'Статей в рубрике'
                ]
            ]
        ] );
    ?>

    <!-- Секция избранная статья -->
    <?php if ( $paged === 1 && have_posts() ) : the_post(); ?>
        <section class="section category-featured">
            <div class="container">

                <!-- Заголовок секции Выбор редакции -->
                <?php
                    get_template_part( 'template-parts/components/section-header', null, [
                        'data' => [
                            'header_title' => 'Выбор редакции'
                        ]
                    ] );
                ?>

                <!-- Главный пост секции -->
                <?php 
                    get_template_part( 'template-parts/components/card-post', null, [
                        'layout' => 'featured'
                    ]);
                ?>

            </div>
        </section>
    <?php endif; ?>

    <!-- Навигация подкатегорий основной категории -->
    <div class="page-category__tags l-bordered-section">
        <div class="container">
            <?php get_template_part( 'template-parts/components/category-cloud' ); ?>
        </div>
    </div>

    <!-- Секция все статьи рубрики -->
    <?php if ( have_posts() ) : ?>
        <section class="section category-archive section--alt">
            <div class="container">

                <!-- Заголовок секции Все статьи и рубрики -->
                <?php
                    get_template_part( 'template-parts/components/section-header', null, [
                        'data' => [
                            'header_title' => 'Все статьи рубрики'
                        ]
                    ] );
                ?>               

                <!-- Сетка записей -->
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