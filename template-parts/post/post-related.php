<?php
// Секция похожие записи
$post_id = get_the_ID();
$category_ids = wp_get_post_categories( $post_id, ['fields' => 'ids'] );

if ( empty( $category_ids ) || is_wp_error( $category_ids ) ) return;

$args = [
	'category__in' => $category_ids,
	'post__not_in' => [$post_id],
	'orderby' => 'rand',
	'posts_per_page' => 3,
	'ignore_sticky_posts' => 1,
];

$related_query = new WP_Query( $args );

if ( $related_query->have_posts() ) :
?>

<section class="section section-related section--white">
	<div class="container">

		<!-- Заголовок секции -->
		<?php
			get_template_part( 'template-parts/components/section-header', null, [
				'section_label' => 'Похожие записи',
				'section_title' => 'Смотрите также',
				'section_description' => 'Изучите другие статьи на похожую тему.',
				'section_alignment' => 'row',
			]);
		?>

		<!-- Сетка похожих записей -->

		<div class="post-grid">

			<?php
				while ( $related_query->have_posts() ) :
					
					$related_query->the_post();

					get_template_part( 'template-parts/components/card-post' );

				endwhile;

				wp_reset_postdata();
			?>

		</div>
	</div>
</section>

<?php endif; ?>