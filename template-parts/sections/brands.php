<?php
// Верстка секции производителей

$brands = get_sub_field( 'brands' );

$section_header = airliner_prepare_header_args( get_sub_field( 'section_header' ), $args );

if ( empty( $brands ) ) return;
?>

<!-- Секция популярные производители -->
<section id="brands" class="section section-brands section--white">
    <div class="container">
		
		<?php get_template_part( 'template-parts/components/section-header', null, $section_header ); ?>

		<ul class="l-grid brands-grid">
			
			<?php foreach ($brands as $brand) : ?>
				<li class="brands-grid__item">
					<?php get_template_part( 'template-parts/components/card-brand', null, array( 'current_brand' => $brand ) ); ?>
				</li>
			<?php endforeach; ?>
		
		</ul>
		
    </div>
</section>