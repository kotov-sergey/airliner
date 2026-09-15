<?php
// Карточка типа фюзеляжа

// Получение текущего типа фюзеляжа
$fuselage_type = $args['current_type'] ?? null;
if ( ! $fuselage_type ) return;

// Ссылка на тип фюзеляжа
$fuselage_type_link = get_term_link( $fuselage_type );
if ( is_wp_error( $fuselage_type_link ) ) return;

// Данные карточки типа фюзеляжа
$label = get_field( 'label', $fuselage_type);
$image = get_field( 'image', $fuselage_type);

// Характеристики конкретного типа фюзеляжа
$planes = get_field( 'examples', $fuselage_type );
$capacity = get_field( 'capacity', $fuselage_type );
$range = get_field( 'range', $fuselage_type );

// ALT-аттрибут для изображения
$alt_text = $fuselage_type->name . ' самолёты';

// Путь к картинке-заглушке
$placeholder = get_template_directory_uri() . '/public/images/placeholder-image.svg';
?>

<article class="card-fuselage">

    <!-- Блок с изображение и подписью карточки -->
    <div class="card-fuselage__picture">

        <!-- Изображение карточки -->
        <?php if ( $image ) : ?>
            <?php echo wp_get_attachment_image( $image, 'large', false, ['class' => 'card-fuselage__image', 'alt' => $alt_text] ); ?>
        <?php else : ?>
            <img src="<?php echo esc_url( $placeholder ); ?>" class="card-fuselage__image" alt="<?php echo esc_attr( $alt_text ); ?>" />
        <?php endif; ?>

        <!-- Подпись карточки -->
        <?php if ( $label ) : ?>
            <span class="pill pill--sm pill--subtle card-fuselage__label"><?php echo esc_html( $label ); ?></span>
        <?php endif; ?>

    </div>

    <div class="card-fuselage__body">

        <!-- Заголовок карточки -->
        <h3 class="card-fuselage__title">
            <a href="<?php echo esc_url( $fuselage_type_link ); ?>" class="card-fuselage__link" aria-label="Перейти на категорию: <?php echo esc_attr( $fuselage_type->name ); ?>">
                <?php echo esc_html( $fuselage_type->name ); ?>
            </a>
        </h3>

        <!-- Описание карточки -->
        <div class="card-fuselage__description">
            <?php echo wp_kses_post( $fuselage_type->description ); ?>
        </div>

        <!-- Характеристики карточки -->
        <?php if ( $capacity || $range ) : ?>
            
            <div class="card-fuselage__specs">
                
                <!-- Вместимость -->
                <?php if ( $capacity ) : ?>

                    <div class="spec-row spec-row--clean-icon">
                        <div class="spec-row__name">
                            <span class="spec-row__label">Вместимость</span>
                        </div>
                        <div class="spec-row__data">
                            <span class="spec-row__value"><?php echo esc_html( $capacity ); ?></span>
                        </div>
                    </div>
                    
                <?php endif; ?>

                <!-- Дальность -->
                 <?php if ( $range ) : ?>

                    <div class="spec-row spec-row--clean-icon">
                        <div class="spec-row__name">
                            <span class="spec-row__label">Дальность</span>
                        </div>
                        <div class="spec-row__data">
                            <span class="spec-row__value"><?php echo esc_html( $range ); ?></span>
                        </div>
                    </div>

                <?php endif; ?>           

            </div>

        <?php endif; ?>

        <!-- Примеры авиалайнеров -->
        <?php if ( $planes ) : ?>
            <div class="card-fuselage__examples">

                <span class="card-fuselage__subtitle">Примеры</span>

                <!-- Тэги для примеров авилайнеров -->
                <div class="card-fuselage__tags">
                    
                    <?php foreach ( $planes as $plane ) :
                        $plane_title = get_the_title( $plane );
                        $plane_link = get_permalink( $plane );
                    ?>

                        <?php if ( $plane_link && ! is_wp_error( $plane_link ) ) : ?>

                            <!-- Тэг-пример авиалайнера -->
                            <a href="<?php echo esc_url( $plane_link ); ?>" class="pill pill--sm pill--subtle card-fuselage__tag">
                                <?php echo esc_html( $plane_title ); ?>
                            </a>

                        <?php else : ?>
                            
                            <!-- Текст-заглушка авиалайнера -->
                            <span class="pill pill--sm pill--subtle card-fuselage__tag">
                                <?php echo esc_html( $plane_title ); ?>
                            </span>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            </div>
        <?php endif; ?>

    </div>

</article>
