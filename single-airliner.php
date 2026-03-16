<?php 
// Шаблон детальной страницы авиалайнера

get_header();

$post_id = get_the_ID();
?>

<main class="page-airliner">

	<?php while ( have_posts() ) :
		the_post();
	?>
	
		<!-- Верстка Hero-секции -->
		<?php get_template_part( 'template-parts/airliner/hero' ); ?>

		<!-- Верстка секции Галерея -->
		<?php get_template_part( 'template-parts/airliner/gallery' ); ?>

		<!-- Верстка секции Технические характеристики -->
		<?php get_template_part( 'template-parts/airliner/specs' ); ?>

		<!-- Верстка секции Контента -->
		<?php get_template_part( 'template-parts/airliner/content' ); ?>
	
	<?php endwhile; ?>
	
</main>

<?php get_footer(); ?>