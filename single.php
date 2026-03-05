<?php get_header(); ?>

<div class="page-wrapper">
	<div class="container">
		<div class="content-layout">
			<div id="content" class="content-layout__main--full">
				<main id="primary" class="main-content">

					<?php if ( have_posts() ) :
					while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', 'single' );

					// Подключаем комментарии, если нужны
					if ( comments_open() || get_comments_number() ) :
					comments_template();
					endif;

					endwhile;
					else :
					get_template_part( 'template-parts/content', 'none' );
					endif; ?>
				</main>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>