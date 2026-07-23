<?php

// Верстка одной карточки бренда

$brand = $args['current_brand'] ?? null;

if ( ! $brand ) return;

$brand_link = get_term_link( $brand );
if ( is_wp_error( $brand_link ) ) return;

$logo = get_field( 'brand_logo', $brand );
$brand_country = get_field( 'brand_country', $brand );
?>

<a href="<?php echo esc_url( $brand_link ); ?>" class="card-brand">
	<div class="card-brand__picture">
		
		<!-- Логотип производителя -->
		<?php if ( $logo ) : ?>
			<img 
				src="<?php echo esc_url( $logo['url'] ); ?>" 
				class="card-brand__image" 
				alt="Логотип производителя <?php echo esc_attr( $brand->name ); ?>" 
				loading="lazy" 
			/>
		<?php else : ?>
			<img 
				src="<?php echo esc_url( get_template_directory_uri() . '/public/images/placeholder-image.svg' ); ?>" 
				class="card-brand__image" 
				alt="Логотип производителя <?php echo esc_attr( $brand->name ); ?>" 
				loading="lazy" 
			/>
		<?php endif; ?>

	</div>

	<div class="card-brand__body">
		
		<!-- Название производителя -->
		<h3 class="card-brand__title"><?php echo esc_html( $brand->name ); ?></h3>
		
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
</a>