<?php
// Шаблон страницы Блога (home.php)

get_header();
?>

<main class="site-main page-blog">
	<div class="container">

		<?php if ( have_posts() ) : the_post(); ?>

			<!-- Секция первого featured поста -->
			<section class="section page-blog__featured">
				<?php get_template_part( 'template-parts/components/card-post', null, [
					'layout' => 'featured'
				] );
				?>
			</section>
		<?php endif; ?>

		<!-- Секция категорий блога -->
		<section class="page-blog__categories">
			<?php
			$categories = get_categories();
			foreach ( $categories as $category ) {
				echo '<a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a> ';
			}
            ?>
		</section>

		<!-- Секция свежих материалов -->
        <?php if ( have_posts() ) : ?>
            <section class="section page-blog__fresh">
                <h2>Свежие материалы</h2>
                
                <div class="posts-grid">
                    <?php
                    for ( $i=0; $i<3; $i++ ) {
                        if ( !have_posts() ) break;
                        the_post();
                        get_template_part( 'template-parts/components/card-post', null, [
                            'layout' => 'overlay' 
                        ] );
                    }
                    ?>
                </div>
            </section>
        <?php endif; ?>

        <!-- Секция все публикации-->
        <?php if ( have_posts() ) : ?>
            <section class="section page-blog__archive">
                <h2>Все публикации</h2>

                <div class="grid-archive">
                    <?php 
                    while ( have_posts() ) : the_post();
                        get_template_part( 'template-parts/components/card-post' );
                    endwhile;
                    ?>
                </div>
            </section>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>