<?php
// Общий шаблон таксономии

$current_term = get_queried_object();

$brand_image = get_field( 'brand_logo', $current_term );
$seo_text = get_field( 'seo_text', $current_term );

get_header();
?>

<main class="site-main page-taxonomy">

    <!--Hero-секция таксономии-->
    <section class="section taxonomy-hero">
        <div class="container">
        
            <div class="taxonomy-hero__wrapper">
                <div class="taxonomy-hero__media">
                    <?php if ( $brand_image ) : ?>
                        <?php 
                            echo wp_get_attachment_image( $brand_image['id'], 'medium', false, array( 'class' => 'taxonomy-hero__image' ) ); 
                        ?>
                    <?php endif; ?>
                    <h1 class="taxonomy-hero__title"><?php echo esc_html( $current_term->name ); ?></h1>
                </div>

                <div class="taxonomy-hero__description"><?php echo wp_kses_post( wpautop( $current_term->description ) ); ?></div>
            </div>

        </div>
    </section>

    <!--Секция каталог таксономии-->
    <section class="section catalog-content page-taxonomy__content">
        <div class="container">
            <div class="catalog-content__layout">

                <!-- Сайдбар (фильтры) -->
                <aside class="catalog-content__sidebar">
                    <?php 
                        get_template_part( 'template-parts/components/catalog-filter', null, [
                            'columns' => 2
                        ] ); 
                    ?>
                </aside>

                <!-- Контейнер для результатов -->
                <div class="catalog-content__results" id="catalog-results">
                    <div class="l-grid l-grid--2 catalog-content__grid">

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
        <section class="section taxonomy-seo section--white">
            <div class="container container--narrow">
                <div class="entry-content">
                    <?php echo wp_kses_post( $seo_text ); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
