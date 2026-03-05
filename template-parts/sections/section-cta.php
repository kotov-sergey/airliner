<?php 
// Верстка секции призыва к действию
$image_data = get_sub_field( 'section_background' );

$title = get_sub_field( 'section_title' );
$description = get_sub_field( 'section_description' );

$button_data = get_sub_field( 'section_button' );
?>

<!-- Секция призыва к действию -->
<section class="section section-cta">

    <div class="section-cta__background">
        <?php
            if ( $image_data ) {
                echo wp_get_attachment_image( $image_data['id'], 'large', false, array(
                    'class' => 'section-cta__image',
                    'loading' => 'lazy',
                ) );
            }
        ?>
    </div>

    <div class="container">
        <div class="section-cta__content">
            <?php if ( $title ) : ?>
                <h3 class="section-cta__title"><?php echo esc_html( $title ); ?></h3>
            <?php endif; ?>

            <?php if ( $description ) : ?>
                <p class="section-cta__description"><?php echo esc_html( $description ); ?></p>
            <?php endif; ?>

            <?php if ( $button_data ) :
                $button_title = $button_data['title'];
                $button_url = $button_data['url'];
                $button_target = $button_data['target'] ? $button_data['target'] : '_self';
            ?>
                <a href="<?php echo esc_url( $button_url ); ?>"
                class="btn btn--primary section-cta__button"
                target="<?php echo esc_attr( $button_target ); ?>">
                    <?php echo esc_html( $button_title ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>

</section>