<?php
// Верстка компонента заголовка (для секций)

$section_number = $args['number'] ?? '';
$header_data = $args['data'] ?? null;

if ( ! $header_data || empty( $header_data['header_title'] ) ) {
    return;
}

$section_label = $header_data['header_label'] ?? '';
$section_title = $header_data['header_title'];
$section_description = $header_data['header_description'] ?? '';

$section_alignment = $header_data['header_alignment'] ?: 'left';
$section_tag = $header_data['header_tag'] ?: 'h2';

$section_modifier = 'section-header--' . $section_alignment;

if ( ! $section_title ) return;
?>

<div class="section-header <?php echo esc_attr( $section_modifier ); ?>">

	<!-- Блок с мета-данными -->
	<?php if ( $section_number || $section_label ) : ?>
		<div class="section-header__meta">
			
			<?php if ( $section_number ) : ?>
				<span class="section-header__number"><?php echo esc_html( $section_number ); ?></span>
			<?php endif; ?>

			<?php if ( $section_label ) : ?>
				<span class="section-header__label"><?php echo esc_html( $section_label ); ?></span>
			<?php endif; ?>

		</div>
	<?php endif; ?>

	<!-- Заголовок + Описание -->
	<div class="section-header__content">

		<!-- Заголовок секции -->
		<<?php echo esc_attr( $section_tag ); ?> class="section-header__title">
			<?php echo esc_html( $section_title ); ?>
		</<?php echo esc_attr( $section_tag ); ?>>

		<!-- Описание секции -->
		<?php if ( $section_description ) : ?>
			<p class="section-header__description"><?php echo esc_html( $section_description ); ?></p>
		<?php endif; ?>
	
	</div>

</div>