<?php
// Верстка компонента заголовка (для секций)

$section_index = $args['index'] ?? ''; 

$section_label = $args['section_label'] ?? '';
$section_title = $args['section_title'] ?? '';
$section_description = $args['section_description'] ?? '';

$has_meta = ( $section_index || $section_label);

$layout_mode = $has_meta ? 'row' : 'column';
$section_modifier = 'section-header--' . $layout_mode;

if ( ! $section_title ) return;
?>

<div class="section-header <?php echo esc_attr( $section_modifier ); ?>">

	<!-- Блок с мета-данными -->
	<?php if ( $has_meta ) : ?>
		<div class="section-header__meta">
			
			<?php if ( $section_index ) : ?>
				<span class="section-header__number"><?php echo esc_html( $section_index ); ?></span>
			<?php endif; ?>

			<?php if ( $section_label ) : ?>
				<span class="section-header__label"><?php echo esc_html( $section_label ); ?></span>
			<?php endif; ?>

		</div>
	<?php endif; ?>

	<!-- Заголовок + Описание -->
	<div class="section-header__content">

		<h2 class="section-header__title"><?php echo esc_html( $section_title ); ?></h2>

		<?php if ( $section_description ) : ?>
			<p class="section-header__description"><?php echo esc_html( $section_description ); ?></p>
		<?php endif; ?>
	
	</div>

</div>