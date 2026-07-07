<?php
// Секция: CTA-блок

$section_title = get_sub_field( 'section_title' );
$section_description = get_sub_field( 'section_description' );
$section_button = get_sub_field( 'section_button' );

if ( ! $section_title && ! $section_button ) return; 
?>

<section class="section cta-block">
    <div class="container">

        <div class="cta-block__inner">

            <!-- Заголовок CTA-блока -->
            <?php if ( $section_title ) : ?>
                <h2 class="cta-block__title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>

            <!-- Описание CTA-блока -->
            <?php if ( $section_description ) : ?>
                <div class="cta-block__description">
                    <?php echo wp_kses_post( wpautop( $section_description ) ); ?>
                </div>
            <?php endif; ?>

            <!-- Кнопка CTA-блока -->
            <?php if ( $section_button && is_array( $section_button ) ) : 
            
                $btn_target = ! empty ($section_button['target']) ? $section_button['target'] : '_self';
            ?>
                <a class="btn btn--outline cta-block__btn"
                    href="<?php echo esc_url( $section_button['url']); ?>" 
                    target="<?php echo esc_attr( $btn_target ); ?>">
                        <?php echo esc_html( $section_button['title'] ); ?>
                </a>
            <?php endif; ?>

        </div>

    </div>
</section>