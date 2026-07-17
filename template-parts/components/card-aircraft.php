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
			<?php the_airliner_badges( ['manufacturer', 'body-type'], '', 'pill--text-only' ); ?>
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
		<?php
			$specs_to_show = [
				['group' => 'specs_performance', 'field' => 'max_speed'],
				['group' => 'specs_weight', 'field' => 'passengers'],
				['group' => 'specs_performance', 'field' => 'range'],
			];

			the_airliner_specs_wrapper( $specs_to_show, $spec_mods );
		?>

		<!-- Кнопка Призыв к действию -->
		<?php if ( $layout === 'vertical' ) : ?>

			<div class="card-aircraft__actions">
				<span class="btn btn--primary card-aircraft__btn-details">Подробнее</span>
			</div>
		<?php endif; ?>
	
</article>