<?php
// Верстка секции производителей

$brands = get_sub_field( 'brands' );

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

if ( empty( $brands ) ) return;
?>

<!-- Секция популярные производители -->
<section id="brands" class="section section-brands section--white">
    <div class="container">
		
		<?php 
			get_template_part( 'template-parts/components/section-header', null, [
				'data' => $section_header,
				'number' => $section_index
			] ); 
		?>

		<ul class="l-grid brands-grid">
			
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