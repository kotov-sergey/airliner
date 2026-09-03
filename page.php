<?php
// Общий шаблон страницы (page.php)

get_header();
?>

<main class="site-main page-default">

    <?php while ( have_posts() ) : the_post(); ?>

        <!-- Шапка страницы -->
        <?php get_template_part( 'template-parts/components/page-header' ); ?>

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
        <?php get_template_part( 'template-parts/builder' ); ?>

  <?php endwhile; ?>

</main>

<?php get_footer(); ?>
