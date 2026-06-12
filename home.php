<?php
// Шаблон страницы Блога (home.php)

get_header();
?>

<main class="site-main page-blog">

    <?php if ( have_posts() ) : the_post(); ?>

        <!-- Секция первого featured поста -->
        <section class="section page-blog__featured">
            <div class="container">
                <?php get_template_part( 'template-parts/components/card-post', null, [
                    'layout' => 'featured'
                ] );
                ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Секция категорий блога -->
    <section class="page-blog__categories">
        <div class="container">
            <?php
            $categories = get_categories();
            foreach ( $categories as $category ) {
                echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a> ';
            }
            ?>
        </div>
    </section>

    <!-- Секция свежих материалов -->
    <?php if ( have_posts() ) : ?>
        <section class="section page-blog__fresh">
            <div class="container">
                <h2 class="page-blog__section-title">Свежие материалы</h2>
                
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
        <section class="section page-blog__archive">
            <div class="container">
                <h2 class="page-blog__section-title">Все публикации</h2>

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
    $blog_page_id = get_option( 'page_for_posts' );

    get_template_part( 'template-parts/builder', null, [
        'page_id' => $blog_page_id
    ] ); 
    ?>
        
</main>

<?php get_footer(); ?>