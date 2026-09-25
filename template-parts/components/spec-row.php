<?php
// Компонент: Строка характеристики

$icon = $args['icon'] ?? '';
$label = $args['label'] ?? '';
$value = $args['value'] ?? '';
$unit = $args['unit'] ?? '';
$is_winner = $args['is_winner'] ?? 'false';

$css_mod = $args['css_mod'] ?? '';

$raw_classes = 'spec-row';
if ( $is_winner ) {
    $raw_classes .= ' is-best';
}

if ( ! empty( $css_mod ) ) {
    $mods_array = explode( ' ', $css_mod );
    foreach ( $mods_array as $mod ) {
        $mod = trim( $mod );
        if ( ! empty( $mod ) ) {
            $raw_classes .= ' spec-row--' . $mod;
        }
    }
}
?>

<div class="<?php echo esc_attr( $raw_classes ); ?>" title="<?php echo esc_attr( $label ); ?>">

    <div class="spec-row__name">
        <?php if ( ! empty( $icon ) ) : ?>
            <div class="spec-row__icon"><?php echo ( $icon ); ?></div>
        <?php endif; ?>

        <?php if ( ! empty( $label) ) : ?>
            <span class="spec-row__label"><?php echo esc_html( $label ); ?></span>
        <?php endif; ?>
    </div>

    <div class="spec-row__data">
        <span class="spec-row__value"><?php echo esc_html( $value ); ?></span>
        
        <?php if ( ! empty( $unit) ) : ?>
            <span class="spec-row__unit"><?php echo esc_html( $unit ); ?></span>
        <?php endif; ?>
    </div>
    
</div>