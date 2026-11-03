<?php
// Верстка компонента заголовка (для секций)

$section_number = $args['number'] ?? '';
$header_data = $args['data'] ?? null;

// Проверка наличия данных
if ( ! is_array( $header_data ) || empty( $header_data['header_title'] ) ) {
    return;
}

// Основные данные
$section_label = $header_data['header_label'] ?? '';
$section_title = $header_data['header_title'];
$section_description = $header_data['header_description'] ?? '';

// Варианты оформления
$section_theme = $header_data['header_theme'] ?? 'default';
$section_alignment = $header_data['header_alignment'] ?? 'row';
$section_tag = $header_data['header_tag'] ?? 'h2';

// Разрешенные значения
$allowed_themes = [ 'default', 'inverse' ];
$allowed_alignments = [ 'row', 'column' ];
$allowed_tags = [ 'h2', 'h3', 'h4', 'h5', 'h6' ];

// Проверка темы оформления
if ( ! in_array( $section_theme, $allowed_themes, true ) ) {
    $section_theme = 'default';
}

// Проверка выравнивания
if ( ! in_array( $section_alignment, $allowed_alignments, true ) ) {
    $section_alignment = 'row';
}

// Проверка HTML-тега
if ( ! in_array( $section_tag, $allowed_tags, true ) ) {
    $section_tag = 'h2';
}

// Формирование классов
$section_classes = [
    'section-header',
    'section-header--' . $section_theme,
    'section-header--' . $section_alignment,
];
?>

<div class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">

	<!-- Блок с мета-данными -->
	<?php if ( $section_number && $section_label ) : ?>
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
		<<?php echo $section_tag; ?> class="section-header__title">
			<?php echo esc_html( $section_title ); ?>
		</<?php echo $section_tag; ?>>

		<!-- Описание секции -->
		<?php if ( $section_description ) : ?>
			<p class="section-header__description"><?php echo esc_html( $section_description ); ?></p>
		<?php endif; ?>
	
	</div>

</div>