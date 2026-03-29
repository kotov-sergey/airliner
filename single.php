<?php
//Шаблон страницы одной записи
$description = get_the_excerpt();
?>
<?php get_header(); ?>

<main class="post-single">

	<?php while( have_posts() ) : the_post(); ?>

		<article class="single-post">
			

			<header class="single-post__header">

				<?php if( has_post_thumbnail() ) : ?>
					<div class="single-post__thumbnail">
						<?php the_post_thumbnail( 'large' ); ?>
					</div>
				<?php endif; ?>

				<div class="single-post__overlay"></div>

				<div class="container">
					<div class="single-post__meta">
						<?php get_template_part( 'template-parts/components/post-meta' ); ?>
					</div>
				
					<h1 class="single-post__title">
						<?php the_title(); ?>
					</h1>

					<p class="single-post__description">
						<?php echo esc_html( $description ); ?>
					</p>
				</div>
			
			</header>

			<div class="container">
				<div class="single-post__content entry-content">
					<?php the_content(); ?>
				</div>
			</div>

		</article>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>