<?php

// Верстка секции последние статьи

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$posts_query = new WP_Query([
	'post_type' => 'post',
	'posts_per_page' => 3,
	'ignore_sticky_posts' => 1,
	'orderby' => 'DATE',
	'order' => 'DESC'
]);
?>

<!-- Секция последние статьи -->
<section class="section section-recent-posts section--white">
  <div class="container">

    <?php 
		get_template_part('template-parts/components/section-header', null, [
			'data' => $section_header,
			'number' => $section_index
		]); 
	?>

	<?php if ( $posts_query && $posts_query->have_posts() ) : ?>

		<div class="l-bento-grid">

			<?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>

				<?php get_template_part( 'template-parts/components/card', 'post', array (
					'layout' => 'overlay',
				) ); ?>

			<?php endwhile; ?>
		
		</div>

		<?php wp_reset_postdata(); ?>
	
	<?php endif; ?>

  </div>
</section>