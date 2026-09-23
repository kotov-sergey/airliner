<?php
// Верстка секции характеристик авиалайнера

$config = get_airliner_specs_config();

$has_specs_data = false;

foreach ( $config as $group_key => $group_data ) {
    $acf_group_values = get_field( $group_key );

    if ( $acf_group_values && is_array( $acf_group_values ) ) {
        if ( ! empty( array_filter( $acf_group_values ) ) ) {
            $has_specs_data = true;
            break;
        }
    }
}
?>

<!-- Верстка секции Технические характеристики -->
<?php if ( $has_specs_data ) : ?>
    <section class="section section-specs">
        <div class="container">
    
            <!-- Заголовок секции -->
            <?php
                get_template_part( 'template-parts/components/section-header', null, [
                    'number' => '03',
                    'data' => [
                        'header_label' => 'Характеристики',
                        'header_title' => 'Технические характеристики',
                        'header_description' => 'Полный список характеристик ' . get_the_title() .  ' с данными от производителя.'
                    ]
                ]);
            ?>

            <!-- Сетка технических характеристик -->
            <div class="l-grid l-grid--2">

                <!-- Компонент группы характеристик авиалайнера -->
                <?php get_template_part( 'template-parts/components/specs-group' ); ?>

            </div>
        
        </div>
    </section>
<?php endif; ?>