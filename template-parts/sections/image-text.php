<?php
// Секция: Изображение + Текст

$section_image = get_sub_field( 'section_image' );
$section_title = get_sub_field( 'section_title' );
$section_description = get_sub_field( 'section_description' );

$image_position = get_sub_field( 'image_position' ) ?: 'left';
$css_class = 'image-text__grid image-text__grid--image-' . $image_position;

$section_bg_class = get_sub_field( 'section_background' ) ?? 'section--gray';

if ( ! $section_image && ! $section_title ) return;
?>

<section class="section image-text <?php echo esc_attr(  $section_bg_class ); ?>">
    <div class="container">

        <div class="<?php echo esc_attr( $css_class ); ?>">

            <!-- Блок с изображением -->
            <div class="image-text__media">
                <?php if ( $section_image ) : ?>
                    <?php echo wp_get_attachment_image( $section_image, 'full', false, ['class' => 'image-text__image'] ); ?>
                <?php endif; ?>
            </div>

            <!-- Блок с контентом -->
            <div class="image-text__content">
                <?php if ( $section_title ) : ?>
                    <h2 class="image-text__title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>

                <?php if ( $section_description ) : ?>
                    <div class="image-text__description">
                        <?php echo wp_kses_post( wpautop( $section_description ) ); ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>