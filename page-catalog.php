<?php

/* Template Name: Каталог авиалайнеров */

get_header();  
?>

<main class="site-main page-catalog">

	<?php while ( have_posts() ) : the_post(); ?>

		<section class="page-catalog__hero">
			<div class="container">
				<h1 class="page-catalog__title"><?php the_title(); ?></h1>
			</div>
		</section>
    <?php endwhile; ?>


    <section class="section page-catalog__content">
        <div class="container">
            <div class="page-catalog__layout">

                <aside class="page-catalog__sidebar">
                    <!-- Форма фильтра -->
                    <form id="airliner-filter" class="page-catalog__filter">
                        <h3>Тип фюзеляжа</h3>
                        
                        <?php
                        $types = get_terms( ['taxonomy' => 'body-type'] );
                        foreach ( $types as $type ) : ?>
                            <label>
                                <input type="checkbox" name="fuselage[]" value="<?php echo $type->term_id; ?>" >
                                <?php echo $type->name; ?><br>
                            </label>
                        <?php endforeach; ?>
                    
                        <h3>Скорость от:</h3>
                        <input type="number" name="max_speed" placeholder="Например: 800">

                        <button type="submit" class="btn btn--primary">Применить</button>
                    </form>
                </aside>


                <div class="page-catalog__results" id="catalog-results">
                    <div class="page-catalog__grid">

                        <?php
                            $catalog_query = new WP_Query([
                                'post_type'=> 'airliner',
                                'posts_per_page'=> 12,
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
                </div>

            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>