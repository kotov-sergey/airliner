<?php
// Карточка авиалайнера

// Получение ID текущей карточки
$post_id = get_the_ID();

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
	
	<!-- Изображение авиалайнера -->
	<div class="card-aircraft__picture">
		
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'large', [ 'class' => 'card-aircraft__image', 'alt' => $alt_text, 'loading' => 'lazy' ] ); ?>
		<?php else : ?> 
			<img src="<?php echo esc_url( $placeholder); ?>" class="card-aircraft__image" alt="<?php echo esc_attr( $alt_text ); ?>" loading="lazy" />
		<?php endif; ?>

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
					echo wp_trim_words( $excerpt, 10, '&hellip;');
				?>
			</p>

		<?php endif; ?>
		
		<!-- Характеристики лайнера -->
		<?php
			$specs_to_show = [
				['group' => 'specs_performance', 'field' => 'max_speed'],
				['group' => 'specs_weight', 'field' => 'passengers'],
				['group' => 'specs_performance', 'field' => 'range'],
			];

			the_airliner_specs_wrapper( $specs_to_show, $spec_mods );
		?>

		<!-- Если карточка Вертикальная -->
		<?php if ( $layout === 'vertical' ) : ?>

			<!-- Кнопка карточки -->
			<div class="card-aircraft__actions">
				<span class="btn btn--secondary card-aircraft__btn">Подробнее</span>
			</div>
		<?php endif; ?>

	</div>
	
</article>