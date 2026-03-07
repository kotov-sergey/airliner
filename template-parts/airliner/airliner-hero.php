<!-- Верстка Hero-секции -->
		<section class="section airliner-hero">
			<div class="container">
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
								<?php the_airliner_badges( ['manufacturer', 'body-type', 'airliner-status'], 'detail' ); ?>
							</div>

							<h1 class="info-card__title">
								<?php the_title(); ?>
							</h1>

							<p class="info-card__description">
								<?php $excerpt = get_the_excerpt(); ?>
								<?php echo $excerpt; ?>
							</p>

							<div class="info-card__specs">
								<?php the_airliner_spec('specs_weight', 'passengers', 'spec-row--hero'); ?>
								<?php the_airliner_spec('specs_performance', 'range', 'spec-row--hero'); ?>
								<?php the_airliner_spec('specs_performance', 'max_speed', 'spec-row--hero'); ?>
								<?php the_airliner_spec('specs_weight', 'mtow', 'spec-row--hero'); ?>
							</div>

						</div>
					</div>
				
				</div>
			</div>
		</section>