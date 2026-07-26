<?php
// Секция обратной связи

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$shortcode = get_sub_field( 'shortcode' );

$section_bg_class = get_sub_field( 'section_background' ) ?: 'section--gray';

?>
<section class="section feedback <?php echo esc_attr( $section_bg_class ); ?>">
    <div class="container">

        <!-- Блок заголовка секции -->
        <?php 
            get_template_part( 'template-parts/components/section-header', null, [
                'data' => $section_header,
                'number' => $section_index
            ]);
        ?>

        <!-- Обёртка контактной формы-->
        <div class="feedback__wrapper custom-form-wrapper">
            <?php
                if ( $shortcode ) {
                    echo do_shortcode( $shortcode );
                }
                else {
                    echo do_shortcode( '[fluentform id="3"]' ); 
                }
            ?>
        </div>

    </div>
</section>