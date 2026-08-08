<?php

// Верстка секции авиалайнеров

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section section-aircrafts ' . $section_background );

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
<section class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">
		
		<?php 
			get_template_part( 'template-parts/components/section-header', null, [
				'data' => $section_header,
				'number' => $section_index
			] ); 
		?>
		
		<?php if ( $airliners_query && $airliners_query->have_posts() ) : ?>
			
			<div class="l-grid l-grid--2">
				
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