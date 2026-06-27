<?php
// Общий шаблон страницы (page.php)

get_header();
?>

<main class="site-main page-default">

    <?php while ( have_posts() ) : the_post(); ?>

        <!-- Стандартный контент -->
        <section class="section page-content">
            <div class="container container--narrow">
                
                <h1 class="page-title"><?php the_title(); ?></h1>

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
