<?php
// Секция: Наши преимущества

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$card_style = get_sub_field( 'card_style' ) ?: 'default';
$section_bg_class = get_sub_field( 'section_background' ) ?: 'section--gray';

$grid_class    = 'l-grid l-grid--auto'; 
$card_modifier = ''; 

if ( $card_style === 'default' ) {
    $grid_class = 'l-grid l-grid--3';
    $card_modifier = '';
}
elseif ( $card_style === 'compact' ) {
    $grid_class = 'l-grid l-grid--auto';
    $card_modifier = 'advantage-card--compact';
}
elseif ( $card_style === 'horizontal' ) {
    $grid_class = 'l-grid l-grid--2';
    $card_modifier = 'advantage-card--horizontal';
}
?>

<section class="section advantages-showcase <?php echo esc_attr( $section_bg_class ); ?>">
    <div class="container">

        <!-- Блок заголовка секции -->
        <?php 
            get_template_part( 'template-parts/components/section-header', null, [
                'data' => $section_header,
                'number' => $section_index
            ]);
        ?>
        
        <?php if ( have_rows( 'section_cards' ) ) : ?>

            <!-- Сетка карточек преимуществ -->
            <div class="<?php echo esc_attr( $grid_class ); ?>">

                <?php while ( have_rows( 'section_cards' ) ) : the_row(); ?>
                   
                    <?php 
                        get_template_part( 'template-parts/components/advantage-card', null, [
                            'card_icon' => get_sub_field( 'card_icon' ),
                            'card_title' => get_sub_field( 'card_title' ),
                            'card_description' => get_sub_field( 'card_description' ),
                            'modifier' => $card_modifier
                        ] );
                    ?>
                
                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <p class="text-mute">Пока не было добавлено карточек преимуществ.</p>
        
        <?php endif; ?>

    </div>
</section>