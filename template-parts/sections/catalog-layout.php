<?php
// Универсальный шаблон каталога (Сайдбар + Сетка + Пагинация)

global $wp_query;
$query = $args['query'] ?? $wp_query;

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>

<div class="catalog-content__layout">

    <!-- Сайдбар (фильтры) -->
    <aside class="catalog-content__sidebar">
        <?php get_template_part( 'template-parts/components/catalog-filter' ); ?>
    </aside>

    <!-- Контейнер для результатов -->
    <div class="catalog-content__results" id="catalog-results">
        
        <div class="l-grid l-grid--3 catalog-content__grid">

            <?php
                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        get_template_part( 'template-parts/components/card-aircraft' );
                    }
                    wp_reset_postdata();
                }
                else {
                    echo '<p>Самолеты не найдены!</p>';
                }
            ?>

        </div>

        <!-- Пагинация -->
        <?php if ( $query->max_num_pages > 1 ) : ?>
            <div class="pagination catalog-content__pagination">
                <?php
                    echo paginate_links( array( 
                        'total' => $query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '&larr; Назад',
                        'next_text' => 'Вперёд &rarr;',
                    ) );
                ?>
            </div>
        <?php endif; ?>

    </div>

</div>