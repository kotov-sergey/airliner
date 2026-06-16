<?php
// Секция статей, связанных с конкретным термином

$term = $args['term'] ?? null;
if ( !$term ) return;

$query_args = [
    'post_type' => 'post',
    'posts_per_page' => 3,
    'ignore_sticky_posts' => 1,
    'tax_query' => [
        [
            'taxonomy' => $term->taxonomy,
            'field' => 'term_id',
            'terms' => $term->term_id,
        ]
    ]
];

$related_query = new WP_Query( $query_args );
?>

<?php if ( $related_query->have_posts() ) : ?>
    <section class="section section-related-posts">
        <div class="container">
            <h2 class="section-related-posts__title">Статьи о <?php echo esc_html( $term->name ); ?></h2>

            <div class="l-grid l-grid--3">

                <?php
                    while ( $related_query->have_posts() ) : $related_query->the_post();

                        get_template_part( 'template-parts/components/card-post' );
                    
                    endwhile;

                    wp_reset_postdata();
                ?>

            </div>
        </div>
    </section>
<?php endif; ?>