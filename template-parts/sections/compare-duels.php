<?php
// Секция дуэлей авиалайнеров

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section faq-block ' . $section_background );

$duels = get_sub_field( 'duels' );

if ( empty( $duels ) ) {
    $duels = [
        [ 'plane_1' => 100, 'plane_2' => 200 ],
        [ 'plane_1' => 300, 'plane_2' => 400 ],
    ];
}
?>

<section class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">

        <!-- Заголовок секции -->
        <?php 
            get_template_part( 'template-parts/components/section-header', null, [
                'data' => $section_header,
                'number' => $section_index
            ]);
        ?>

        <!-- Сетка карточек дуэлей -->
        <div class="compare-duels__grid l-grid l-grid--2">

            <?php foreach ( $duels as $duel ) :
                $id1 = $duel['plane_1'] ?? 0;
                $id2 = $duel['plane_2'] ?? 0;

                if ( ! $id1 || ! $id2 ) continue;
            ?>
                <!-- Компонент карточки дуэлей -->
                <?php 
                    get_template_part( 'template-parts/components/card-duel', null, [
                        'plane_1' => $id1,
                        'plane_2' => $id2
                    ] ); 
                ?>
            <?php endforeach; ?>

        </div>

    </div>
</section>