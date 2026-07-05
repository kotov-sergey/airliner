<?php
// Секция: Наши преимущества

$section_title = get_sub_field( 'section_title' );
$section_subtitle = get_sub_field( 'section_subtitle' );
?>

<section class="section advantages-showcase">
    <div class="container">

        <?php if ( $section_title ) : ?>
            <div class="advantages-showcase__header">
                
                <h2 class="advantages-showcase__title"><?php echo esc_html( $section_title ); ?></h2>
                
                <?php if ( $section_subtitle ) : ?>
                    <p class="advantages-showcase__subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        
        <?php if ( have_rows( 'section_cards' ) ) : ?>

            <div class="l-grid l-grid--3">

                <?php while ( have_rows( 'section_cards' ) ) : the_row();

                    $card_icon = get_sub_field( 'card_icon' );
                    $card_title = get_sub_field( 'card_title' );
                    $card_description = get_sub_field( 'card_description' );
                ?>
                    <?php 
                        get_template_part( 'template-parts/components/advantage-card', null, [
                            'card_icon' => $card_icon,
                            'card_title' => $card_title,
                            'card_description' => $card_description
                        ] );
                     ?>
                
                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <p class="text-mute">Пока не было добавлено карточек преимуществ.</p>
        
        <?php endif; ?>

    </div>
</section>