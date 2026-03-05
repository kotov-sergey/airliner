<?php get_header(); ?>

<div class="page-wrapper">
	<div class="container">
		<div class="content-layout content-layout--with-sidebar">
			<div id="content" class="content-layout__main">
				<main id="primary" class="main-content">

					<div class="archive-wrapper">

						<header class="archive-header">
							<h1 class="archive-title">
								<?php the_archive_title(); ?>
							</h1>
						</header>

						<?php if ( have_posts() ) : ?>

							<?php while ( have_posts() ) : the_post(); ?>
								<?php get_template_part( 'template-parts/content' ); ?>
							<?php endwhile; ?>

							<div class="archive-pagination">
								<?php
								the_posts_pagination( array(
									'mid_size'  => 2,
									'prev_text' => __( '« Назад', 'mytheme' ),
									'next_text' => __( 'Вперёд »', 'mytheme' ),
								) );
								?>
							</div>

						<?php else : ?>
							<?php get_template_part( 'template-parts/content', 'none' ); ?>
						<?php endif; ?>

					</div>
				</main>
			</div>
				<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>