<?php
// CTA-блок

$title = $args['section_title'] ?? get_sub_field( 'section_title' );
$description = $args['section_description'] ?? get_sub_field( 'section_description' );
$button = $args['section_button'] ?? get_sub_field( 'section_button' );
?>

<section class="section section-cta-block">
    <div class="container">
        
        <div class="section-cta-block__wrapper"> 
            <div class="section-cta-block__content">
                <h2 class="section-cta-block__title"><?php echo esc_html( $title ); ?></h2>
                
                <?php if ( $description ) : ?>
                    <p class="section-cta-block__description"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>
            </div>

            <div class="section-cta-block__action">
                <?php if ( $button ) :
                    $button_target = $button['target'] ? $button['target'] : '_self';
                ?>
                    <a href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>" class="btn btn--primary">
                        <?php echo esc_html( $button['title'] ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    
    </div>
</section>