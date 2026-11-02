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
        get_template_part( 'template-parts/components/pages/page-header', null, [
            'title' => $blog_title,
            'description' => $blog_description,
            'show_breadcrumbs' => true
        ] );
    ?>

    <!-- Навигация по категориям блога -->
    <div class="page-blog__categories l-bordered-section">
        <div class="container">
            <?php get_template_part( 'template-parts/components/navigation/terms-nav' ); ?>
        </div>
    </div>    

    <?php if ( have_posts() ) : the_post(); ?>

        <!-- Секция первого Featured-поста -->
        <section class="section blog-featured section--alt">
            <div class="container">

                <!-- Заголовок секции Featured-поста -->
                <?php
                    get_template_part( 'template-parts/components/ui/section-header', null, [
                        'number' => '01',
                        'data' => [
                            'header_label' => 'Редакция',
                            'header_title' => 'Выбор редакции',
                            'header_description' => 'Ознакомьтесь с выбором нашей редакции'
                        ]
                    ] );
                ?>

                <!-- Карточка Featured-поста -->
                <?php 
                    get_template_part( 'template-parts/components/cards/card-post', null, [
                        'layout' => 'featured'
                    ] );
                ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Секция свежих материалов -->
    <?php if ( have_posts() ) : ?>
        <section class="section blog-fresh">
            <div class="container">

                <!-- Заголовок секции Свежие материалы -->
                <?php
                    get_template_part( 'template-parts/components/ui/section-header', null, [
                        'number' => '02',
                        'data' => [
                            'header_label' => 'Материалы',
                            'header_title' => 'Свежие материалы',
                            'header_description' => 'Ознакомтесь со свежими материалами'
                        ]
                    ] );
                ?>
                
                <!-- Bento-сетка записей -->
                <div class="l-bento-grid">
                    <?php
                    for ( $i=0; $i<3; $i++ ) {
                        if ( !have_posts() ) break;
                        $card_variant = ( $i === 0 ) ? 'featured' : 'compact';

                        the_post();
                        get_template_part( 'template-parts/components/cards/card-post', null, [
                            'layout' => 'overlay',
                            'variant' => $card_variant
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
                    
                    get_template_part( 'template-parts/components/ui/section-header', null, [
                        'number' => '03',
                        'data' => [
                            'header_label' => 'Публикации',
                            'header_title' => 'Все публикации',
                            'header_description' => 'Ознакомтесь со всеми публикациями'
                        ]
                    ] );
                ?>

                <!-- Сетка записей -->
                <div class="l-grid l-grid--3">
                    <?php while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/components/cards/card-post' );
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