<?php
// Карточка авиалайнера

// Получение ID текущей карточки
$plane_id = get_the_ID();

// Раскладка карточки авиалайнера
$layout = $args['layout'] ?? 'vertical';
$card_class = 'card-aircraft card-aircraft--' . $layout;

// Модификаторы для характеристик авиалайнера
$spec_mods = 'clean-icon';
if ( $layout === 'horizontal' ) {
	$spec_mods .= ' no-label';
}

// Alt для изображения
$alt_text = 'Самолет ' . get_the_title() . ' на взлетной полосе';

// Путь к картинке-заглушке
$placeholder = get_template_directory_uri() . '/public/images/placeholder-image.svg';
?>

<article class="<?php echo esc_attr( $card_class ); ?>">
	
	<!-- Блок для изображения авиалайнера -->
	<div class="card-aircraft__picture">
		
		<!-- Изображение авиалайнера -->
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', [ 'class' => 'card-aircraft__image', 'alt' => $alt_text, 'loading' => 'lazy' ] ); ?>
		<?php else : ?> 
			<img src="<?php echo esc_url( $placeholder); ?>" class="card-aircraft__image" alt="<?php echo esc_attr( $alt_text ); ?>" loading="lazy" />
		<?php endif; ?>

		<!-- Кнопка добавления к сравнению -->
		<button 
			type="button"
			data-id="<?php echo esc_attr( $plane_id ); ?>" 
			class="card-aircraft__compare js-compare-btn"
			aria-label="Добавить к сравнению">

			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" aria-hidden="true">
				<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 12H3M16 6H3M16 18H3M18 9v6M21 12h-6"></path>
			</svg>
		</button>

	</div>

	<div class="card-aircraft__body">
		
		<!-- Мета-данные авиалайнера -->
		<div class="card-aircraft__meta">
			<?php the_airliner_badges( ['manufacturer', 'body-type'], '', 'pill--text-only' ); ?>
		</div>

		<!-- Название авиалайнера -->
		<h3 class="card-aircraft__title">
			<a href="<?php the_permalink(); ?>" class="card-aircraft__link">
				<?php the_title(); ?>
			</a>
		</h3>

		<!-- Если карточка Горизонтальная -->
		<?php if ( $layout === 'horizontal' ) : ?>
			
			<!-- Описание карточки -->
			<p class="card-aircraft__description">
				<?php
					$excerpt = get_the_excerpt();
					echo esc_html( wp_trim_words( $excerpt, 10, '&hellip;') );
				?>
			</p>

		<?php endif; ?>
		
		<!-- Характеристики авиалайнера -->
		<div class="card-aircraft__specs">
			<?php
				get_template_part( 'template-parts/components/specs-key', null, [
					'plane_id' => $plane_id,
					'css_mod' => $spec_mods
				] );
			?>
		</div>

		<!-- Если карточка Вертикальная -->
		<?php if ( $layout === 'vertical' ) : ?>

			<!-- Кнопка карточки -->
			<div class="card-aircraft__actions">
				<span class="btn btn--secondary card-aircraft__btn">Подробнее</span>
			</div>
		<?php endif; ?>

	</div>
	
</article>