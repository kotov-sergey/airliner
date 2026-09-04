<?php
// Универсальная шапка страницы (Page-header)

$title = $args['title'] ?? get_the_title(); // Заголовок
$description = $args['description'] ?? ''; // Описание
$stats = $args['stats'] ?? []; // Массив с инфографикой
?>

<header class="page-header" style="--bg-pattern: url('<?php echo esc_url( get_template_directory_uri() . '/public/images/header-pattern.png' ); ?>');">
    <div class="container page-header__container">
        
        <!-- Контент секции -->
        <div class="page-header__content">

            <!-- Заголовок + Описание секции -->
            <div class="page-header__text">

                <!-- Хлебные крошки -->
                <?php if ( function_exists( "rank_math_the_breadcrumbs" ) ) : ?>
                    <div class="breadcrumbs breadcrumbs--inverse page-header__breadcrumbs">
                        <?php rank_math_the_breadcrumbs(); ?>
                    </div>
                <?php endif; ?>

                <!-- Заголовок секции -->
                <h1 class="page-header__title"><?php echo esc_html( $title ); ?></h1>

                <!-- Описание секции -->
                <?php if ( $description ) : ?>
                    <div class="page-header__description">
                        <?php echo wp_kses_post( wpautop( $description ) ); ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Инфографика (если передали массив) -->
            <?php if ( ! empty( $stats ) ) : ?>
                <div class="page-header__stats">

                    <?php foreach ( $stats as $stat ) : ?>
                        <!-- Карточка статистики -->
                        <div class="catalog-stat">
                            <span class="catalog-stat__number"><?php echo esc_html( $stat['number'] ); ?></span>
                            <span class="catalog-stat__label"><?php echo esc_html( $stat['label'] ); ?></span>
                        </div>
                    <?php endforeach; ?>

                </div>
            <?php endif; ?>

        </div>

    </div>
</header>