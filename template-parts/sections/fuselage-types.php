<?php
// Верстка секции тип фюзеляжа

$fuselage_types = get_sub_field( 'fuselage_type' );

$section_header = airliner_prepare_header_args( get_sub_field( 'section_header' ), $args );

if ( empty( $fuselage_types ) ) return;
?>

<!-- Секция тип фюзеляжа -->
<section class="section section-fuselage-types">
    <div class="container">
		
        <?php get_template_part( 'template-parts/components/section-header', null, $section_header ); ?>

        <div class="l-grid l-grid--3">

            <?php foreach( $fuselage_types as $fuselage_type ) : ?>
                <?php 
                    get_template_part( 'template-parts/components/card-fuselage', null, [
                        'current_type' => $fuselage_type
                    ] );
                ?>
            <?php endforeach; ?>

        </div>
		
    </div>
</section>