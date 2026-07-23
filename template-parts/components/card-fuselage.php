<?php
// Верстка карточки тип фюзеляжа

$fuselage_type = $args['current_type'] ?? null;
if ( ! $fuselage_type ) return;


$fuselage_type_link = get_term_link( $fuselage_type );
if ( is_wp_error( $fuselage_type_link ) ) return;

$label = get_field( 'label', $fuselage_type);
$image = get_field( 'image', $fuselage_type);

$planes = get_field( 'examples', $fuselage_type );
$capacity = get_field( 'capacity', $fuselage_type );
$range = get_field( 'range', $fuselage_type );
?>

<div class="card-fuselage">
    <div class="card-fuselage__picture">

        <?php if ( $image ) : ?>
            <?php echo wp_get_attachment_image( $image, 'large', false, ['class' => 'card-fuselage__image'] ); ?>
        <?php endif; ?>

        <?php if ( $label ) : ?>
            <span class="pill pill--sm pill--subtle card-fuselage__badge"><?php echo esc_html( $label ); ?></span>
        <?php endif; ?>

    </div>

    <div class="card-fuselage__body">
        <a href="<?php echo esc_url( $fuselage_type_link ); ?>" class="card-fuselage__link" aria-label="Перейти на категорию: <?php echo esc_attr( $fuselage_type->name ); ?>">
            <h3 class="card-fuselage__title">
                <?php echo esc_html( $fuselage_type->name ); ?>
            </h3>
        </a>

        <p class="card-fuselage__description">
            <?php echo wp_kses_post( $fuselage_type->description ); ?>
        </p>

        <dl class="card-fuselage__specs">
            
            <?php if ( $capacity ) : ?>
                <div class="card-fuselage__spec-row">
                    <dt>Вместимость</dt>
                    <dd><?php echo esc_html( $capacity ); ?></dd>
                </div>
            <?php endif; ?>

             <?php if ( $range ) : ?>
                <div class="card-fuselage__spec-row">
                    <dt>Дальность</dt>
                    <dd><?php echo esc_html( $range ); ?></dd>
                </div>
            <?php endif; ?>           

        </dl>

        <?php if ( $planes ) : ?>
            <div class="card-fuselage__examples">

                <span class="card-fuselage__examples-title">Примеры</span>

                <div class="card-fuselage__tags">
                    
                    <?php foreach ( $planes as $plane ) :
                        $plane_title = get_the_title( $plane );
                        $plane_link = get_permalink( $plane );
                    ?>

                        <?php if ( $plane_link && ! is_wp_error( $plane_link ) ) : ?>

                            <a href="<?php echo esc_url( $plane_link ); ?>">
                                <span class="pill pill--sm pill--subtle card-fuselage__tag"><?php echo esc_html( $plane_title ); ?></span>
                            </a>

                        <?php else : ?>
                            
                            <span class="pill pill--sm pill--subtle card-fuselage__tag"><?php echo esc_html( $plane_title ); ?></span>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>
            </div>
            
        <?php endif; ?>

    </div>
</div>
