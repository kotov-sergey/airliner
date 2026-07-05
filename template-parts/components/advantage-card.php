<?php
// Карточка преимуществ

$card_icon = $args['card_icon'] ?? '';
$card_title = $args['card_title'] ?? 'Заголовок карточки';
$card_description = $args['card_description'] ?? 'Описание карточки';

$card_layout = $args['card_layout'] ?? 'default';
?>

<article class="advantage-card advantage-card--<?php echo esc_attr( $card_layout ); ?>">

    <?php if ( $card_icon ) : ?>
        <div class="advantage-card__media">
            <?php echo airliner_get_svg( 'advantages/' . $card_icon ); ?>
        </div>
    <?php endif; ?>

    <div class="advantage-card__content">
        <?php if ( $card_title ) : ?>
            <h3 class="advantage-card__title"><?php echo esc_html( $card_title ); ?></h3>
        <?php endif; ?>

        <?php if ( $card_description ) : ?>
            <p class="advantage-card__description"><?php echo esc_html( $card_description ); ?></p>
        <?php endif; ?>
    </div>

</article>