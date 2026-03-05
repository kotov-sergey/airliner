<?php

// Верстка заголовка секций на главной

$section_index = $args['index'] ?? ''; 

$label = $args['section_label'] ?? '';
$title = $args['section_title'] ?? '';
$description = $args['section_description'] ?? '';

$section_alignment = $args['section_alignment'] ?? 'row';

$section_modifier = 'section-header--' . $section_alignment;

if ( ! $title ) return;
?>

<div class="section-header <?php echo esc_attr( $section_modifier ); ?>">
	<div class="section-header__group">
		<div class="section-header__meta">
			<span class="section-header__number"><?php echo esc_html( $section_index ); ?></span>
			<span class="section-header__divider"></span>
			<span class="section-header__label"><?php echo esc_html( $label ); ?></span>
		</div>

		<h2 class="section-header__title"><?php echo esc_html( $title ); ?></h2>
	</div>

	<p class="section-header__description"><?php echo esc_html ( $description ); ?></p>

</div>