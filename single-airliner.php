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
		<?php get_template_part( 'template-parts/airliner/airliner', 'hero' ); ?>

		<!-- Верстка секции Галерея -->
		<?php get_template_part( 'template-parts/airliner/airliner', 'gallery' ); ?>

		<!-- Верстка секции Технические характеристики -->
		<?php get_template_part( 'template-parts/airliner/airliner', 'specs' ); ?>

		<!-- Верстка секции Контента -->
		<?php get_template_part( 'template-parts/airliner/airliner', 'content' ); ?>

		<!-- Верстка секции Похожие авиалайнеры -->
		<?php get_template_part( 'template-parts/airliner/airliner', 'related' ); ?>		
	
	<?php endwhile; ?>
	
</main>

<?php get_footer(); ?>