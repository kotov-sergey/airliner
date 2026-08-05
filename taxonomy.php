<?php
// Общий шаблон таксономии

$current_term = get_queried_object();

$brand_image = get_field( 'brand_logo', $current_term );

$hero_background = get_field( 'taxonomy_hero_bg', $current_term );
$hero_title = $current_term->name;
$hero_description = $current_term->description;

$seo_text = get_field( 'seo_text', $current_term );

get_header();
?>

<main class="site-main page-taxonomy">

    <!--Hero-секция таксономии-->
    <?php
        get_template_part( 'template-parts/components/hero', null, [
            'title' => $hero_title ? $hero_title : get_the_title(),
            'description' => $hero_description,
            'background_image' => $hero_background
        ] );
    ?>

    <!--Секция каталог таксономии-->
    <section class="section catalog-content page-taxonomy__content">
        <div class="container">
            <div class="catalog-content__layout">

                <!-- Сайдбар (фильтры) -->
                <aside class="catalog-content__sidebar">
                    <?php 
                        get_template_part( 'template-parts/components/catalog-filter', null, [
                            'columns' => 3
                        ] ); 
                    ?>
                </aside>

                <!-- Контейнер для результатов -->
                <div class="catalog-content__results" id="catalog-results">
                    <div class="l-grid l-grid--3 catalog-content__grid">

                        <?php
                            if ( have_posts() ) {
                                while ( have_posts() ) {
                                    the_post();
                                    get_template_part( 'template-parts/components/card-aircraft' );
                                }
                            }
                            else {
                                echo '<p>Самолеты не найдены!</p>';
                            }
                        ?>

                    </div>

                    <!-- Пагинация -->
                    <?php if ( $wp_query->max_num_pages > 1 ) : ?>
                        <div class="catalog-content__pagination">
                            <?php
                            echo paginate_links( array( 
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

    <!--Секция связанные статьи таксономии-->
    <?php 
        get_template_part( 'template-parts/post/post-related-by-term', null, [
            'term' =>$current_term
        ] );
    ?>

    <!--Секция SEO-текст таксономии-->
    <?php if ( $seo_text ) : ?>
        <section class="section taxonomy-seo">
            <div class="container container--narrow">
                <div class="entry-content">
                    <?php echo wp_kses_post( $seo_text ); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
