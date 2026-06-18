<?php
// Шаблон страницы Блога (home.php)

$blog_page_id = get_option( 'page_for_posts' );
$blog_title = get_field( 'blog_title', $blog_page_id ) ?: 'Блог';

get_header();
?>

<main class="site-main page-blog">

    <!-- Hero-секция Блога -->
    <section class="blog-hero">
        <div class="container blog-hero__container">
            <h1 class="blog-hero__title"><?php echo esc_html( $blog_title ); ?></h1>
        </div>
    </section>

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
    <nav class="blog-categories">
        <div class="container">
            <ul class="blog-categories__list">
                    <?php
                        $categories = get_categories();
                        foreach ( $categories as $category ) {
                            echo '<li class="blog-categories__item">
                                    <a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="blog-categories__link">' . esc_html( $category->name ) . '</a>
                                  </li>';
                        }
                    ?>
            </ul>
        </div>
    </nav>

    <!-- Секция свежих материалов -->
    <?php if ( have_posts() ) : ?>
        <section class="section blog-fresh">
            <div class="container">
                <h2 class="blog-fresh__title">Свежие материалы</h2>
                
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
                <h2 class="blog-archive__title">Все публикации</h2>

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