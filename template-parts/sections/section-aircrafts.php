<?php

// Верстка секции авиалайнеров

$section_header = airliner_prepare_header_args( get_sub_field( 'section_header' ), $args );

$ids = get_sub_field( 'related_aircrafts' );

$specs_to_show = array( 'range', 'speed', 'seats' );

$airliners_query = null;

if ( !empty( $ids ) ) {
$airliners_query = new WP_Query([
	'post_type' => 'airliner',
	'post__in' => $ids,
	'orderby' => 'post__in',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => 4
]);
}
?>

<!-- Секция популярные авиалайнеры -->
<section class="section section-aircrafts">
    <div class="container">
		
		<?php get_template_part( 'template-parts/components/section-header', null, $section_header ); ?>
		
		<?php if ( $airliners_query && $airliners_query->have_posts() ) : ?>
			
			<div class="aircrafts-grid">
				
				<?php while ( $airliners_query->have_posts() ) : $airliners_query->the_post(); ?>
				
					<?php get_template_part( 'template-parts/components/card', 'aircraft', array(
						'show_specs' => $specs_to_show,
						'layout' => 'horizontal',
					) ); ?>
				
				<?php endwhile; ?>

			</div>
		
			<?php wp_reset_postdata(); ?>
		
		<?php endif; ?>
		
    </div>
</section>