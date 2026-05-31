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
                        <div class="page-catalog__block">
                            <h3>Производитель</h3>
                            <?php
                            $brands = get_terms( ['taxonomy' => 'manufacturer'] );
                            foreach ( $brands as $brand ) : ?>
                                <label>
                                    <input type="checkbox" name="brand[]" value="<?php echo $brand->term_id; ?>" class="form-checkbox" >
                                    <?php echo $brand->name; ?><br>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="page-catalog__block">
                            <h3>Тип фюзеляжа</h3>
                            <?php
                            $types = get_terms( ['taxonomy' => 'body-type'] );
                            foreach ( $types as $type ) : ?>
                                <label>
                                    <input type="checkbox" name="fuselage[]" value="<?php echo $type->term_id; ?>" class="form-checkbox" >
                                    <?php echo $type->name; ?><br>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="page-catalog__block">
                            <h3>Статус</h3>
                            <?php
                            $statuses = get_terms( ['taxonomy' => 'airliner-status'] );
                            foreach ( $statuses as $status ) : ?>
                                <label>
                                    <input type="checkbox" name="status[]" value="<?php echo $status->term_id; ?>" class="form-checkbox" >
                                    <?php echo $status->name; ?><br>
                                </label>
                            <?php endforeach; ?>
                        </div>                        
                    
                        <div class="page-catalog__block">
                            <h3>Дальность полёта</h3>
                            <input type="number" name="range" class="form-input" placeholder="Например: 15 000">
                        </div>

                        <div class="page-catalog__block">
                            <h3>Вместимость</h3>
                            <input type="number" name="passengers" class="form-input" placeholder="Например: 1 000">
                        </div>                       

                        <button type="submit" class="btn btn--primary">Применить</button>
                    </form>
                </aside>

                <!-- Контейнер для результатов -->
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