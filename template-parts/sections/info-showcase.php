<?php
// Секция: Информация + Инфографика

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_description = get_sub_field( 'section_description' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section info-showcase ' . $section_background );
?>

<section class="<?php echo esc_attr( $classes ); ?>">
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
                            get_template_part( 'template-parts/components/card-advantage', null, [
                                'card_icon' => get_sub_field( 'card_icon' ),
                                'card_title' => get_sub_field( 'card_title' ),
                                'card_description' => get_sub_field( 'card_description' ),
                                'modifier' => 'card-advantage--horizontal'
                            ]);
                        ?>
            
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        
        </div>

    </div>
</section>