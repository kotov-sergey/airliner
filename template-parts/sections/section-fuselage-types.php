<?php

// Верстка секции тип фюзеляжа

$section_header = airliner_prepare_header_args( get_sub_field( 'section_header' ), $args );
$fuselage_types = get_sub_field( 'fuselage_type' );
?>

<!-- Секция тип фюзеляжа -->
<section class="section section-fuselage-types">
    <div class="container">
		
        <?php get_template_part( 'template-parts/components/section-header', null, $section_header ); ?>

        <div class="fuselage-types-grid">

            <?php if ( $fuselage_types ) : ?>
                
                <?php foreach( $fuselage_types as $fuselage_type ) : ?>
                    
                    <?php get_template_part( 'template-parts/components/card', 'fuselage', array( 'current_type'=>$fuselage_type ) ); ?>
                
                <?php endforeach; ?>

            <?php endif; ?>

        </div>
		
    </div>
</section>