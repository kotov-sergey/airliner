<?php
// Карточка статьи

$layout = $args['layout'] ?? 'standard';
?>

<?php if ( $layout === 'overlay' ) : ?>

	<article class="promo-card">

		<div class="promo-card__background">
			<?php the_post_thumbnail('large', array (
				'class' => 'promo-card__image',
				'alt' => $title,
			)); ?>
		</div>

		<div class="promo-card__content">

			<div class="promo-card__meta">
				<?php get_template_part( 'template-parts/components/post-meta' ); ?>
			</div>

			<h3 class="promo-card__title">
				<a href="<?php echo get_the_permalink(); ?>" class="promo-card__link" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			
			<div class="promo-card__description">
				<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
			</div>

			<div class="promo-card__action">
				<span class="promo-card__button">Читать</span>

				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M5 12h14M12 5l7 7-7 7"/>
				</svg>
			</div>

		</div>
		
	</article>

<?php else : ?>

	<article class="post-card">

		<div class="post-card__media">
			<?php the_post_thumbnail('large', array (
				'class' => 'post-card__image',
				'alt' => $title,
			)); ?>
		</div>

		<div class="post-card__content">

			<div class="post-card__meta">
				<?php get_template_part( 'template-parts/components/post-meta' ); ?>
			</div>

			<h3 class="post-card__title">
				<a href="<?php echo get_the_permalink(); ?>" class="post-card__link" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			
			<div class="post-card__description">
				<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
			</div>

			<div class="post-card__action">
				<span class="post-card__button">Читать</span>

				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M5 12h14M12 5l7 7-7 7"/>
				</svg>
			</div>

		</div>

	</article>

<?php endif; ?>
