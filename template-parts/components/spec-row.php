<?php
// Компонент: Строка характеристики

$icon = $args['icon'] ?? '';
$label = $args['label'] ?? '';
$value = $args['value'] ?? '';
$unit = $args['unit'] ?? '';
$css_mod = $args['css_mod'] ?? '';

$classes = 'spec-row';

if ( ! empty( $css_mod ) ) {
    $mods_array = explode( ' ', $css_mod );
    foreach ( $mods_array as $mod ) {
        $mod = trim( $mod );
        if ( ! empty( $mod ) ) {
            $classes .= ' spec-row--' . $mod;
        }
    }
}
?>

<div class="<?php echo esc_attr( $classes ); ?>" title="<?php echo esc_attr( $label ); ?>">

    <div class="spec-row__name">
        <div class="spec-row__icon"><?php echo $icon; ?></div>
        <span class="spec-row__label"><?php echo esc_html( $label ); ?></span>
    </div>

    <div class="spec-row__data">
        <span class="spec-row__value"><?php echo esc_html( $value ); ?></span>
        <span class="spec-row__unit"><?php echo esc_html( $unit ); ?></span>
    </div>
    
</div>