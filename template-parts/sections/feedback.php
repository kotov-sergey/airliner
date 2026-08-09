<?php
// Секция обратной связи

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$shortcode = get_sub_field( 'shortcode' );
$section_image = get_sub_field( 'section_image' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section feedback ' . $section_background );

?>
<section class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">

        <!-- Блок заголовка секции -->
        <?php 
            get_template_part( 'template-parts/components/section-header', null, [
                'data' => $section_header,
                'number' => $section_index
            ]);
        ?>

        <div class="feedback__layout">
            
            <!-- Обёртка контактной формы-->
            <div class="feedback__form custom-form-wrapper">
                <?php
                    if ( $shortcode ) {
                        echo do_shortcode( $shortcode );
                    }
                    else {
                        echo do_shortcode( '[fluentform id="3"]' ); 
                    }
                ?>
            </div>

            <!-- Изображение контактной формы -->
            <div class="feedback__media">
                <?php if ( $section_image ) : ?>

                    <?php echo wp_get_attachment_image( $section_image, 'full', false, ['class' => 'feedback__image'] ); ?>
                
                <?php else : ?>

                    <img src="<?php echo esc_url( get_template_directory_uri() . '/public/images/placeholder-image.svg' ); ?>" 
                        class="feedback__image" 
                        loading="lazy" 
                    />
                    
                <?php endif; ?>     
            </div>

        </div>
    </div>
</section>