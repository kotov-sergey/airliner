<?php
// Верстка секции производителей

$brands = get_sub_field( 'brands' );

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section section-brands ' . $section_background );

if ( empty( $brands ) ) return;
?>

<!-- Секция популярные производители -->
<section id="brands" class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">
		
		<?php 
			get_template_part( 'template-parts/components/section-header', null, [
				'data' => $section_header,
				'number' => $section_index
			] ); 
		?>

		<ul class="l-grid l-grid--4">
			
			<?php foreach ($brands as $brand) : ?>
				<li class="brands-grid__item">
					<?php 
						get_template_part( 'template-parts/components/card-brand', null, [
							'current_brand' => $brand
						] );
					?>
				</li>
			<?php endforeach; ?>
		
		</ul>
		
    </div>
</section>