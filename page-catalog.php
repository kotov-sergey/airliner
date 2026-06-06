<?php

/* Template Name: Каталог авиалайнеров */

get_header();  
?>

<main class="site-main page-catalog">

	<?php while ( have_posts() ) : the_post(); ?>

        <!-- Hero-секция -->
		<section class="page-catalog__hero">
			<div class="container">
				<h1 class="page-catalog__title"><?php the_title(); ?></h1>
			</div>
		</section>
    <?php endwhile; ?>

    <section class="section page-catalog__content">
        <div class="container">
            <div class="page-catalog__layout">

                <!-- Сайдбар (фильтры) -->
                <aside class="page-catalog__sidebar">
                    <?php get_template_part( 'template-parts/components/catalog-filter' ); ?>
                </aside>

                <!-- Контейнер для результатов -->
                <div class="page-catalog__results" id="catalog-results">
                    <div class="page-catalog__grid">

                        <?php
                            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                            $catalog_query = new WP_Query([
                                'post_type'=> 'airliner',
                                'posts_per_page'=> 9,
                                'paged' => $paged
                            ]);

                            if ( $catalog_query->have_posts() ) {
                                while ( $catalog_query->have_posts() ) {
                                    $catalog_query->the_post();
                                    get_template_part( 'template-parts/components/card-aircraft' );
                                }
                                wp_reset_postdata();
                            }
                            else {
                                echo '<p>Самолеты не найдены!</p>';
                            }
                        ?>

                    </div>

                    <?php if ( $catalog_query->max_num_pages > 1 ) : ?>
                        <div class="page-catalog__pagination">
                            <?php
                            echo paginate_links( array( 
                                'total' => $catalog_query->max_num_pages,
                                'current' => $paged,
                                'prev_text' => '&larr; Назад',
                                'next_text' => 'Вперёд &rarr;',
                            ) );
                            ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>