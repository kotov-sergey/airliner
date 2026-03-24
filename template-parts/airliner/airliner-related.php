<?php
// Секция похожие авиалайнеры
$post_id = get_the_ID(); // Получаем ID текущего поста
$related_posts = []; // Массив для хранения похожих постов

// Ручной выбор
$manual_related = get_field( 'manual_related' );

if ( $manual_related ) {
    $related_posts = $manual_related;
}

else {
    $term_ids = wp_get_post_terms( $post_id, 'body-type', [ 'fields' => 'ids' ] ); // Получаем id типа фюзеляжа текущего поста

    if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {


        $args = [
            'post_type' => 'airliner',
            'posts_per_page' => 4,
            'orderby' => 'rand',
            'post__not_in' => [$post_id],
            'tax_query' => [ [
                'taxonomy' => 'body-type',
                'field' => 'term_id',
                'terms' => $term_ids,
                ]
            ]
        ];

        $related_query = new WP_Query( $args );

        if ( $related_query->have_posts() ) {
            $related_posts = $related_query->posts;
        }

    }
}
?>

<section class="section section-related">
    <div class="container">

        <!-- Заголовок секции -->
        <?php
        get_template_part( 'template-parts/components/section-header', null, [
            'index' => 5,
            'section_label' => 'Похожие модели',
            'section_title' => 'Вам также будет интересно',
            'section_description' => 'Изучите другие модели этого же класса. Сравните характеристики, вместимость и дальность полета с главными конкурентами.',
            'section_alignment' => 'row',
        ]);
        ?>

        <!-- Сетка похожих записей -->
        <?php if ( ! empty( $related_posts ) ) : ?>

            <div class="airliners-grid">

                <?php
                    global $post;
 
                    foreach ( $related_posts as $post ) :

                    setup_postdata( $post );

                        get_template_part( 'template-parts/components/card-aircraft' );

                    endforeach;

                    wp_reset_postdata();
                ?>

            </div>
        
        <?php else : ?>
        
            <p class="text-muted">Похожих записей пока нет, возможно они появятся в скором времени.</p>

        <?php endif; ?>

    </div>
</section>