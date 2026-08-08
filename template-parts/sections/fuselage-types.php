<?php
// Верстка секции тип фюзеляжа

$fuselage_types = get_sub_field( 'fuselage_type' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section section-fuselage-types ' . $section_background );

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

if ( empty( $fuselage_types ) ) return;
?>

<!-- Секция тип фюзеляжа -->
<section class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">
		
        <?php 
            get_template_part( 'template-parts/components/section-header', null, [
                'data' => $section_header,
                'number' => $section_index
            ]);
        ?>

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