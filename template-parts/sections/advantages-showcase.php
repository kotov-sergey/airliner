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

        
        <?php if ( have_rows( 'section_repeater' ) ) : ?>

            <div class="l-grid l-grid--3">

                <?php while ( have_rows( 'section_repeater' ) ) : the_row();

                    $repeater_icon = get_sub_field( 'repeater_icon' );
                    $repeater_title = get_sub_field( 'repeater_title' );
                    $repeater_description = get_sub_field( 'repeater_description' );
                ?>

                <article class="advantage-card">

                    <?php if ( $repeater_icon ) : ?>
                        <div class="advantage-card__media">
                            <?php echo airliner_get_svg( 'advantages/' . $repeater_icon ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="advantage-card__content">
                        <?php if ( $repeater_title ) : ?>
                            <h3 class="advantage-card__title"><?php echo esc_html( $repeater_title ); ?></h3>
                        <?php endif; ?>

                        <?php if ( $repeater_description ) : ?>
                            <p class="advantage-card__description"><?php echo esc_html( $repeater_description ); ?></p>
                        <?php endif; ?>
                    </div>

                </article>

                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <p class="text-mute">Пока не было добавлено карточек преимуществ.</p>
        
        <?php endif; ?>

    </div>
</section>