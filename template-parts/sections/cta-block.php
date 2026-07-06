<?php
// Секция: CTA-блок

$section_title = get_sub_field( 'section_title' );
$section_description = get_sub_field( 'section_description' );
$section_button = get_sub_field( 'section_button' );

if ( ! is_wp_error( $section_button ) && ! empty( $section_button) ) {
    $btn_title = $section_button['title'];
}
?>

<section class="section cta-block">
    <div class="container cta-block__wrapper">

        <div class="cta-block__inner">

            <h2 class="cta-block__title"><?php echo esc_html( $section_title ); ?></h2>

            <p class="cta-block__description"><?php echo esc_html( $section_description ); ?></p>

            <a class="btn btn--outline cta-block__btn" href="#"><?php echo esc_html( $btn_title ); ?></a>

        </div>

    </div>
</section>