<?php
// Секция: Наши преимущества

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_bg_class = get_sub_field( 'section_background' ) ?: 'section--gray';
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
            <div class="l-grid l-grid--auto">

                <?php while ( have_rows( 'section_cards' ) ) : the_row(); ?>
                   
                    <?php 
                        get_template_part( 'template-parts/components/advantage-card', null, [
                            'card_icon' => get_sub_field( 'card_icon' ),
                            'card_title' => get_sub_field( 'card_title' ),
                            'card_description' => get_sub_field( 'card_description' )
                        ] );
                    ?>
                
                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <p class="text-mute">Пока не было добавлено карточек преимуществ.</p>
        
        <?php endif; ?>

    </div>
</section>