<?php
// Компонент группы характеристик авиалайнера

$plane_id = $args['plane_id'] ?? get_the_ID();
$specs = get_airliner_specs_config();
$winners = $args['winners'] ?? [];

$css_mod = $args['css_mod'] ?? '';

foreach ( $specs as $group_key => $group_data ) :
    $group_values = get_field( $group_key, $plane_id );
    if ( ! $group_values || empty( array_filter( $group_values ) ) ) continue;
?>

    <div class="spec-card">
        <h4 class="spec-card__title">
            <?php echo esc_html( $group_data['label'] ); ?>
        </h4>

        <div class="spec-card__list">
            <?php foreach ( $group_data['fields'] as $field_key => $field_data ) : ?>
                <?php the_airliner_spec( $group_key, $field_key, $group_values, $css_mod, $winners ); ?>
            <?php endforeach; ?>
        </div>

    </div>

<?php endforeach; ?>