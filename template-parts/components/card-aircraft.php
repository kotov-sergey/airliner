<?php

// Верстка одной карточки авиалайнера

$post_id = get_the_ID();

$layout = $args['layout'] ?? 'vertical';
$card_class = 'card-aircraft card-aircraft--' . $layout;

$spec_mods = 'clean-icon'; // По умолчанию иконка без фона
if ( $layout === 'horizontal' ) {
	$spec_mods .= ' no-label'; // Горизонтальная карточка без лейбла
}

// Alt для изображения
$alt_text = 'Самолет ' . get_the_title() . ' на взлетной полосе';
?>

<article class="<?php echo esc_attr( $card_class ); ?>">
	
	<div class="card-aircraft__picture">
		
		<!-- Изображение авиалайнера -->
		<?php 
			if ( has_post_thumbnail() ) :
				the_post_thumbnail( 'large', array(
				'class'   => 'card-aircraft__image',
				'alt' => $alt_text,
				'loading' => 'lazy'              
			) );
			else : 
		?>
			<img 
				src="<?php echo esc_url( get_template_directory_uri() . '/public/images/placeholder-image.svg' ); ?>" 
				class="card-aircraft__image" 
				alt="<?php echo esc_attr( $alt_text ); ?>"
				loading="lazy" 
			/>
		<?php endif; ?>

	</div>

	<div class="card-aircraft__body">
		
		<!-- Верхняя строка: Бренд и Тип фюзеляжа -->
		<div class="card-aircraft__meta">
			<?php the_airliner_badges( ['manufacturer', 'body-type'], 'card' ); ?>
		</div>

		<!-- Название авиалайнера -->
		<a href="<?php the_permalink(); ?>" class="card-aircraft__link">
			<h3 class="card-aircraft__title">
				<?php the_title(); ?>
			</h3>
		</a>

		<?php if ( $layout === 'horizontal' ) : ?>
		
			<p class="card-aircraft__description">
				<?php
					$excerpt = get_the_excerpt();
					echo wp_trim_words( $excerpt, 10, '&hellip;');
				?>
			</p>

		<?php endif; ?>
		
		<!-- Характеристики -->
		<div class="card-aircraft__specs">
			<?php the_airliner_spec( 'specs_performance', 'max_speed', $spec_mods ); ?>
			<?php the_airliner_spec( 'specs_weight', 'passengers', $spec_mods ); ?>
			<?php the_airliner_spec( 'specs_performance', 'range', $spec_mods ); ?>
		</div>

		<!-- Кнопка Призыв к действию -->
		<?php if ( $layout === 'vertical' ) : ?>

			<div class="card-aircraft__actions">
				<span class="btn btn--primary card-aircraft__btn-details">Подробнее</span>

				<button type="button" class="btn btn--outline card-aircraft__btn-compare" aria-label="Добавить к сравнению">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="14" width="4" height="6" rx="1"/>
					<rect x="10" y="9" width="4" height="11" rx="1"/>
					<rect x="17" y="4" width="4" height="16" rx="1"/>
					</svg>
				</button>
			</div>

		<?php else : ?>

			<div class="card-aircraft__actions">
				<span class="card-aircraft__link-text">
					Подробнее

					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
						<path d="M3 12H19" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
						
						<path d="M12 5L19 12L12 19" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</span>
			</div>

		<?php endif; ?>
	
</article>