<?php
// Секция: Информация + Инфографика

$section_title = get_sub_field( 'section_title' );
$section_description = get_sub_field( 'section_description' );

$section_bg_class = get_sub_field ( 'section_background' ) ?? 'section--gray';
?>

<section class="section info-showcase <?php echo esc_attr( $section_bg_class ); ?>">
    <div class="container">

        <div class="info-showcase__grid">

            <?php if ( $section_description ) : ?>
                <div class="info-showcase__content">
                    <?php if ( $section_title ) : ?>
                        <h2 class="info-showcase__title"><?php echo esc_html( $section_title ); ?></h2>
                    <?php endif; ?>

                    <div class="info-showcase__description"><?php echo wp_kses_post( wpautop( $section_description ) ); ?></div>
                </div>
            <?php endif; ?>

            <?php if ( have_rows( 'section_cards' ) ) : ?>
                <div class="info-showcase__infographic">
                    <?php while ( have_rows( 'section_cards') ) : the_row();
                        $card_icon = get_sub_field( 'card_icon' );
                        $card_title = get_sub_field( 'card_title' );
                        $card_description = get_sub_field( 'card_description' );
                    ?>

                        <?php 
                            get_template_part( 'template-parts/components/advantage-card', null, [
                                'card_icon' => $card_icon,
                                'card_title' => $card_title,
                                'card_description' => $card_description,
                                'card_layout' => 'compact'
                            ]);
                        ?>
            
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        
        </div>

    </div>
</section>