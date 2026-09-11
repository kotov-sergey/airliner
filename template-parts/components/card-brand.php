<?php
// Верстка одной карточки производителя (бренда)

// Текущий производитель
$brand = $args['current_brand'] ?? null;
if ( ! $brand ) return;

// Ссылка на производителя
$brand_link = get_term_link( $brand );
if ( is_wp_error( $brand_link ) ) return;

// Логотип производителя
$logo_id = get_field( 'brand_logo', $brand );

// Страна производителя
$brand_country = get_field( 'brand_country', $brand );

// Alt-аттрибут для изображения
$alt_text = 'Логотип производителя' . $brand->name;

// Путь к картинке-заглушке
$placeholder = get_template_directory_uri() . '/public/images/placeholder-image.svg';
?>

<article class="card-brand">

	<!-- Логотип производителя -->
	<div class="card-brand__picture">
		
		<?php if ( $logo_id ) : ?>
			<?php echo wp_get_attachment_image( $logo_id, 'large', false, [ 'class' => 'card-brand__image', 'alt' => $alt_text, 'loading' => 'lazy'] ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( $placeholder ); ?>" class="card-brand__image" alt="<?php echo esc_attr( $alt_text ); ?>" loading="lazy" />
		<?php endif; ?>

	</div>

	<!-- Контент карточки -->
	<div class="card-brand__body">
		
		<!-- Наименование производителя -->
		<h3 class="card-brand__title">
			<a href="<?php echo esc_url( $brand_link ); ?>" class="card-brand__link">
				<?php echo esc_html( $brand->name ); ?>
			</a>
		</h3>
		
		<!-- Мета-описание -->
		<div class="card-brand__meta">
			
			<!-- Страна производителя -->
			<?php if ( ! empty ( $brand_country ) )  : ?>
				<span class="card-brand__country">
					<?php echo esc_html( $brand_country->name ); ?>
				</span>
			<?php endif; ?>
			
			<!-- Кол-во моделей -->
			<span class="card-brand__count">
				<?php 
					echo $brand->count . ' '; 
					echo my_declension( $brand->count, array('модель', 'модели', 'моделей' ) ); 
				?>
			</span>

		</div>

	</div>
</article>