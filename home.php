<?php
// Шаблон страницы Блога (home.php)

$blog_page_id = get_option( 'page_for_posts' );

$blog_title = get_field( 'blog_title', $blog_page_id ) ?: 'Блог';
$blog_description = get_field( 'blog_description', $blog_page_id ) ?? '';

get_header();
?>

<main class="site-main page-blog">

    <!-- Шапка страницы -->
    <?php 
        get_template_part( 'template-parts/components/page-header', null, [
            'title' => $blog_title,
            'description' => $blog_description
        ] );
    ?>

    <?php if ( have_posts() ) : the_post(); ?>

        <!-- Секция первого Featured-поста -->
        <section class="section blog-featured section--alt">
            <div class="container">

                <!-- Заголовок секции Featured-поста -->
                <?php
                    get_template_part( 'template-parts/components/section-header', null, [
                        'data' => [
                            'header_title' => 'Выбор редакции'
                        ]
                    ] );
                ?>

                <!-- Карточка Featured-поста -->
                <?php 
                    get_template_part( 'template-parts/components/card-post', null, [
                        'layout' => 'featured'
                    ] );
                ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Навигация категорий блога -->
    <div class="page-blog__categories l-bordered-section">
        <div class="container">
            <?php 
                get_template_part( 'template-parts/components/category-cloud' ); ?>
        </div>
    </div>

    <!-- Секция свежих материалов -->
    <?php if ( have_posts() ) : ?>
        <section class="section blog-fresh">
            <div class="container">

                <!-- Заголовок секции Свежие материалы -->
                <?php
                    get_template_part( 'template-parts/components/section-header', null, [
                        'data' => [
                            'header_title' => 'Свежие материалы'
                        ]
                    ] );
                ?>
                
                <!-- Bento-сетка записей -->
                <div class="l-bento-grid">
                    <?php
                    for ( $i=0; $i<3; $i++ ) {
                        if ( !have_posts() ) break;
                        the_post();
                        get_template_part( 'template-parts/components/card-post', null, [
                            'layout' => 'overlay' 
                        ] );
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Секция все публикации-->
    <?php if ( have_posts() ) : ?>
        <section class="section blog-archive section--alt">
            <div class="container">

                <!-- Заголовок секции Все публикации -->
                <?php
                    get_template_part( 'template-parts/components/section-header', null, [
                        'data' => [
                            'header_title' => 'Все публикации'
                        ]
                    ] );
                ?>

                <!-- Сетка записей -->
                <div class="l-grid l-grid--3">
                    <?php 
                    while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/components/card-post' );
                    endwhile;
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Секция призыв к действию -->
    <?php
        get_template_part( 'template-parts/builder', null, [
            'page_id' => $blog_page_id
        ] ); 
    ?>
        
</main>

<?php get_footer(); ?>