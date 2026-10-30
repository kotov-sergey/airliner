<?php
// Карточка преимуществ

$card_icon = $args['card_icon'] ?? '';
$card_title = $args['card_title'] ?? 'Заголовок карточки';
$card_description = $args['card_description'] ?? 'Описание карточки';

$modifier = $args['modifier'] ?? '';
$classes = 'card-advantage';

if ( $modifier ) {
    $classes .= ' ' . $modifier;
}
?>

<article class="<?php echo esc_attr( $classes ); ?>">

    <?php if ( $card_icon ) : ?>
        <div class="card-advantage__media">
            <?php echo airliner_get_svg( 'advantages/' . $card_icon ); ?>
        </div>
    <?php endif; ?>

    <div class="card-advantage__content">
        <?php if ( $card_title ) : ?>
            <h3 class="card-advantage__title"><?php echo esc_html( $card_title ); ?></h3>
        <?php endif; ?>

        <?php if ( $card_description ) : ?>
            <div class="card-advantage__description"><?php echo wp_kses_post( wpautop( $card_description ) ); ?></div>
        <?php endif; ?>
    </div>

</article>