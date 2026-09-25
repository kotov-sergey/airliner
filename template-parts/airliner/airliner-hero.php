<?php
// Верстка Hero-секции страницы лайнера

$plane_id = get_the_ID();
?>

<section class="section airliner-hero">
	<div class="container">

		<!-- Хлебные крошки -->
		<?php if ( function_exists( "rank_math_the_breadcrumbs" ) ) : ?>
			<div class="breadcrumbs airliner-hero__breadcrumbs">
				<?php rank_math_the_breadcrumbs(); ?>
			</div>
		<?php endif; ?>

		<!-- Главная карточка с названием и описанием -->
		<div class="airliner-hero__grid">
			
			<div class="airliner-hero__picture">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'large', array( 
						'class' => 'airliner-hero__image' ) );
				}
				?>
			</div>

			<div class="airliner-hero__content">
				<div class="info-card">

					<div class="info-card__meta">
						<?php the_airliner_badges( ['manufacturer', 'body-type', 'airliner-status'], '', 'pill--solid' ); ?>
					</div>

					<h1 class="info-card__title">
						<?php the_title(); ?>
					</h1>

					<p class="info-card__description">
						<?php $excerpt = get_the_excerpt(); ?>
						<?php echo $excerpt; ?>
					</p>

					<div class="l-grid l-grid--2 info-card__specs">

						<!-- Вывод ключевых характеристик авиалайнера -->
						<?php
							get_template_part( 'template-parts/components/specs-key', null, [
								'plane_id' => $plane_id,
								'css_mod' => 'vertical'
							] );
						?>

					</div>

				</div>
			</div>
		
		</div>
	</div>
</section>