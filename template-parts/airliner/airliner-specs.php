<?php
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
    <section class="section section-specs section--white">
        <div class="container">
            
            <h2>Технические характеристики</h2>

            <div class="section-specs__grid">
                <?php
                foreach ( $config as $group_key => $group_data ) :
                    $this_group_values = get_field( $group_key );
                    if ( ! $this_group_values || empty( array_filter( $this_group_values ) ) ) continue;
                ?>
                    <div class="spec-card">
                        <div class="spec-card__title">
                            <?php echo esc_html( $group_data['label'] ); ?>
                        </div>

                        <div class="spec-card__list">
                            <?php
                            foreach ( $group_data['fields'] as $field_key => $field_data ) :
                                the_airliner_spec( $group_key, $field_key );
                            endforeach;
                            ?>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>
        
        </div>
    </section>
<?php endif; ?>