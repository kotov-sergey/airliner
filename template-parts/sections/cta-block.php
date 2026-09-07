<?php
// Секция: CTA-блок

$section_image = $args['section_image'] ?? get_sub_field( 'section_image' ); // Фоновое изображение секции

$section_title = $args['section_title'] ?? get_sub_field( 'section_title' ); // Заголовок секции
$section_description = $args['section_description'] ?? get_sub_field( 'section_description' ); // Описание секции
$section_button = $args['section_button'] ?? get_sub_field( 'section_button' ); // Кнопка секции

$section_modifier = $args['section_modifier'] ?? get_sub_field( 'section_background' ); // Модификатор фона секции

if ( $section_modifier === 'default' || ! $section_modifier ) {
    $section_modifier = '';
}

$section_classes = trim( 'section cta-block ' . $section_modifier );

if ( ! $section_title && ! $section_button ) return; 
?>

<section class="<?php echo esc_attr( $section_classes ); ?>">
    <div class="container">

        <div class="cta-block__inner">
            <div class="cta-block__content">

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
                    <a class="btn btn--primary cta-block__btn"
                        href="<?php echo esc_url( $section_button['url']); ?>" 
                        target="<?php echo esc_attr( $btn_target ); ?>">
                            <?php echo esc_html( $section_button['title'] ); ?>
                    </a>
                <?php endif; ?>

            </div>

            <!-- Изображение секции -->
            <?php if ( $section_image ) : ?>
                <div class="cta-block__media">

                    <?php 

                    // Если передали ID изображения из ACF
                    if ( is_numeric( $section_image ) ) :
                        echo wp_get_attachment_image( $section_image, 'full', false, ['class' => 'cta-block__image'] );

                    // Если передали прямую ссылку (строку) из шаблона
                    elseif ( is_string( $section_image ) ) :
                    ?>
                        <img 
                            src="<?php echo esc_url( $section_image ); ?>" 
                            class="cta-block__image" 
                            alt="Фон призыва к действию" 
                            loading="lazy" 
                        />
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </div>
        
    </div>
</section>