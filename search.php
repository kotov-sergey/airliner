<?php get_header(); ?>
<div class="page-wrapper">
	<div class="container">
		<div class="content-layout content-layout--with-sidebar">
			<div id="content" class="content-layout__main">
				<main id="primary" class="main-content">

					<?php if ( have_posts() ) : ?>

						<header class="page-header">
							<h1 class="page-title">
								<?php
									printf( esc_html__( 'Результаты поиска для: %s', 'your-theme-textdomain' ), '<span>' . get_search_query() . '</span>' );
								?>
							</h1>
						</header><!-- .page-header -->

						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();

							/**
							 * Замените 'content' на название вашего шаблона части записи
							 * (например, 'content-search', 'content-archive' или 'content')
							 */
							get_template_part( 'template-parts/content', 'search' );

						endwhile;

						the_posts_navigation();

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>
				
				</main>
			</div>
				<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>