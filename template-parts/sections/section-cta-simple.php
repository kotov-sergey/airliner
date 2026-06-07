<?php
// CTA-блок

$title = get_sub_field( 'section_title' );
$description = get_sub_field( 'section_description' );
$button = get_sub_field( 'section_button' );
?>

<div class="cta-block">
    
    <div class="cta-block__content">
        <h2 class="cta-block__title"><?php echo esc_html( $title ); ?></h2>
        
        <?php if ( $description ) : ?>
            <p class="cta-block__description"><?php echo esc_html( $description ); ?></p>
        <?php endif; ?>
    </div>

    <div class="cta-block__action">
        <?php if ( $button ) :
            $button_target = $button['target'] ? $button['target'] : '_self';
        ?>
            <a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>" class="btn btn--primary">
                <?php echo esc_html( $button['title'] ); ?>
            </a>
        <?php endif; ?>
    </div>

</div>