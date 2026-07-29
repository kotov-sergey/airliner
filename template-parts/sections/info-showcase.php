<?php
// Секция: Информация + Инфографика

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_description = get_sub_field( 'section_description' );

$section_bg_class = get_sub_field ( 'section_background' ) ?: 'section--gray';
?>

<section class="section info-showcase <?php echo esc_attr( $section_bg_class ); ?>">
    <div class="container">

        <div class="info-showcase__grid">

            <?php if ( $section_header || $section_description ) : ?>
                <div class="info-showcase__content">

                    <?php
                        get_template_part( 'template-parts/components/section-header', null, [
                            'data' => $section_header,
                            'number' => $section_index
                        ] );
                    ?>

                    <div class="info-showcase__description entry-content"><?php echo wp_kses_post( wpautop( $section_description ) ); ?></div>

                </div>
            <?php endif; ?>

            <?php if ( have_rows( 'section_cards' ) ) : ?>
                <div class="info-showcase__infographic">
                    <?php while ( have_rows( 'section_cards') ) : the_row(); ?>

                        <?php 
                            get_template_part( 'template-parts/components/advantage-card', null, [
                                'card_icon' => get_sub_field( 'card_icon' ),
                                'card_title' => get_sub_field( 'card_title' ),
                                'card_description' => get_sub_field( 'card_description' ),
                                'modifier' => 'advantage-card--horizontal'
                            ]);
                        ?>
            
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        
        </div>

    </div>
</section>