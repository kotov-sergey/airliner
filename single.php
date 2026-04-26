<?php
//Шаблон страницы одной записи
?>

<?php get_header(); ?>

<main class="post-single">

	<?php while( have_posts() ) : the_post();
		
		$description = get_the_excerpt();
	
	?>

		<article class="single-post">
			

			<header class="single-post__header">

				<?php if( has_post_thumbnail() ) : ?>
					<div class="single-post__thumbnail">
						<?php 
							the_post_thumbnail( 'full', array(
								'class' => 'single-post__image',
								'loading' => 'eager',
							) ); 
						?>
					</div>
				<?php endif; ?>

				<div class="single-post__overlay"></div>

				<div class="container">
					<div class="single-post__header-content">
						<div class="single-post__meta">
							<?php get_template_part( 'template-parts/components/post-meta' ); ?>
						</div>
					
						<h1 class="single-post__title">
							<?php the_title(); ?>
						</h1>

						<div class="single-post__description">
							<?php echo wp_kses_post( $description ); ?>
						</div>
					</div>
				</div>
			
			</header>

			<div class="container container--narrow">
				<div class="single-post__content entry-content">
					<?php the_content(); ?>
				</div>
			</div>

		</article>

		<?php get_template_part( 'template-parts/post/post', 'related' ); ?>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>