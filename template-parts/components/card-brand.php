<?php

// Верстка одной карточки бренда

$brand = $args['current_brand'] ?? null;

if ( ! $brand ) return;

$logo = get_field( 'brand_logo', $brand );
$brand_country = get_field( 'brand_country', $brand );
?>

<a href="<?php echo esc_url( $brand->link ); ?>" class="card-brand">

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
			<?php if ( $brand_country ) : ?>
				<span class="card-brand__country">
					<?php echo esc_html( $brand_country->name ); ?>
				</span>
			<?php endif; ?>
			
			<!-- Кол-во моделей -->
			<?php if ( $brand->count > 0 ) : ?>
				<span class="card-brand__count">
					<?php 
						echo $brand->count . ' '; 
						echo my_declension( $brand->count, array('модель', 'модели', 'моделей' ) ); 
					?>
				</span>
			<?php else : ?>
				<span class="card-brand__count">
					<?php 
						echo $brand->count . ' '; 
						echo my_declension( $brand->count, array('модель', 'модели', 'моделей' ) ); 
					?>
				</span>
			<?php endif; ?>
		</div>

	</div>

</a>