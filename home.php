<?php
// Шаблон страницы Блога (home.php)

$blog_page_id = get_option( 'page_for_posts' );
$blog_title = get_field( 'blog_title', $blog_page_id ) ?: 'Блог';

get_header();
?>

<main class="site-main page-blog">

    <!-- Hero-секция Блога -->
    <div class="blog-hero">
        <div class="container">
            <h1 class="blog-hero__title"><?php echo esc_html( $blog_title ); ?></h1>
        </div>
    </div>

    <?php if ( have_posts() ) : the_post(); ?>

        <!-- Секция первого featured поста -->
        <section class="section blog-featured">
            <div class="container">
                <?php get_template_part( 'template-parts/components/card-post', null, [
                    'layout' => 'featured'
                ] );
                ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Навигация категорий блога -->
    <div class="page-blog__categories">
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
        <section class="section blog-archive">
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