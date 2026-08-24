<?php
// Общий шаблон страницы (page.php)

get_header();
?>

<main class="site-main page-default">

    <?php while ( have_posts() ) : the_post(); ?>

        <!-- Шапка страницы -->
        <header class="page-header">
            <div class="container">

                <!-- Хлебные крошки -->
                <?php if ( function_exists( "rank_math_the_breadcrumbs" ) ) : ?>
                    <div class="breadcrumbs">
                        <?php rank_math_the_breadcrumbs(); ?>
                    </div>
                <?php endif; ?>

                <!-- Заголовок страницы -->
                <h1 class="page-header__title"><?php the_title(); ?>

            </div>
        </header>

        <!-- Стандартный контент -->
        <section class="section page-content">
            <div class="container">

                <!-- Контент страницы -->
                <?php if ( get_the_content() ) : ?>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>
            
            </div>
        </section>

        <!-- Кастомные секции -->
        <?php get_template_part( 'tempate-parts/builder' ); ?>

  <?php endwhile; ?>

</main>

<?php get_footer(); ?>
