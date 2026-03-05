<?php
// Верстка карточки тип фюзеляжа

$term = $args['current_type'] ?? null;

if ( !$term ) return;

$name = $term->name;
$description = $term->description;
$link = get_term_link( $term );

$image_id = get_field( 'image', $term );
$label = get_field( 'label', $term );

$capacity = get_field( 'capacity', $term );
$range = get_field( 'range', $term );

$examples = get_field( 'examples', $term );
?>

<div class="card-fuselage">

    <div class="card-fuselage__picture">

        <?php if ( $image_id ) : ?>
            <?php echo wp_get_attachment_image( $image_id, 'large', false, ['class' => 'card-fuselage__image'] ); ?>
        <?php endif; ?>

        <?php if ( $label ) : ?>
            <span class="card-fuselage__badge"><?php echo esc_html( $label ); ?></span>
        <?php endif; ?>

    </div>

    <div class="card-fuselage__body">

        <a href="<?php echo esc_url( $link ); ?>" class="card-fuselage__link" aria-label="Перейти на категорию: <?php echo esc_html( $name ); ?>">
            <h3 class="card-fuselage__title">
                <?php echo esc_html( $name ); ?>
            </h3>
        </a>

        <p class="card-fuselage__description">
            <?php echo esc_html( $description ); ?>
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

        <?php if ( $examples ) : ?>
            <div class="card-fuselage__examples">

                <span class="card-fuselage__examples-title">Примеры</span>


                <div class="card-fuselage__tags">
                    
                    <?php foreach ( $examples as $plane ) :
                        
                        $plane_title = get_the_title( $plane );
                        $plane_link = get_permalink( $plane );
                    
                    ?>

                        <?php if ( $plane_link && !is_wp_error( $plane_link ) ) : ?>

                            <a href="<?php echo esc_url( $plane_link ); ?>">
                                <span class="card-fuselage__tag"><?php echo esc_html( $plane_title ); ?></span>
                            </a>

                        <?php else : ?>
                            
                            <span class="card-fuselage__tag"><?php echo esc_html( $plane_title ); ?></span>

                        <?php endif; ?>
                    
                    <?php endforeach; ?>

                </div>
                
            </div>
            
        <?php endif; ?>

    </div>

</div>
