<?php
// Карточка дуэли авиалайнера

$id1 = $args['plane_1'] ?? 0;
$id2 = $args['plane_2'] ?? 0;

if ( ! $id1 || ! $id2 ) return;

// Данные первого самолета
$title1 = get_the_title( $id1 );
$img1 = get_the_post_thumbnail_url( $id1, 'large' );

// Данные второго самолета
$title2 = get_the_title( $id2 );
$img2 = get_the_post_thumbnail_url( $id2, 'large' );

// Модификатор стиля характеристик авиалайнера
$spec_mods = ['clean-icon'];
$spec_mods_string = implode( ' ', $spec_mods );

// Ссылка на страницу сравнения
$compare_url = home_url( '/compare/?ids=' . $id1 . ',' . $id2 );
?>

<div class="card-duel">

    <!-- Первый самолёт -->
    <div class="card-duel__plane">

        <!-- Изображение самолёта 1 -->
        <?php if ( $img1 ) : ?>
            <img src="<?php echo esc_url( $img1); ?>" alt="<?php echo esc_attr( $title1); ?>" loading="lazy" class="card-duel__img" />
        <?php endif; ?>

        <!-- Заголовок самолёта 1 -->
        <h3 class="card-duel__title"><?php echo esc_html( $title1 ); ?></h3>
        
        <!-- Характеристики самолёта 1 -->
        <div class="card-duel__specs">
            <?php
                get_template_part( 'template-parts/components/specs-key', null, [
                    'plane_id' => $id1,
                    'css_mod' => $spec_mods_string
                ] );
            ?>
        </div>


    </div>

    <!-- Значок VS в центре -->
    <div class="card-duel__vs" aria-hidden="true">
        <span>VS</span>
    </div>

    <!-- Второй самолёт -->
    <div class="card-duel__plane">

        <!-- Изображение самолёта 2 -->
        <?php if ( $img2 ) : ?>
            <img src="<?php echo esc_url( $img2); ?>" alt="<?php echo esc_attr( $title2); ?>" loading="lazy" class="card-duel__img" />
        <?php endif; ?>

        <!-- Заголовок самолёта 2 -->
        <h3 class="card-duel__title"><?php echo esc_html( $title2 ); ?></h3>
        
        <!-- Характеристики самолёта 2 -->
        <div class="card-duel__specs">
            <?php
                get_template_part( 'template-parts/components/specs-key', null, [
                    'plane_id' => $id2,
                    'css_mod' => $spec_mods_string
                ] );
            ?>
        </div>

    </div>

    <!-- Кнопка перехода к сравнению пары -->
    <div class="card-duel__action">
        <a href="<?php echo esc_url( $compare_url ); ?>" class="btn btn--primary card-duel__btn">
            Сравнить в деталях
        </a>
    </div>


</div>