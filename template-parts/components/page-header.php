<?php
// Универсальная шапка страницы (Page-header)

$title = $args['title'] ?? get_the_title(); // Заголовок секции
$description = $args['description'] ?? ''; // Описание секции
$bg_image = $args['bg_image'] ?? null; // Фон секции

$modifier = $args['modifier'] ?? 'page-header--default'; // Модификатор секции
$scroll_target = $args['scroll_target'] ?? ''; // Кнопка-якорь секции

$show_meta = $args['show_meta'] ?? false; // Мета-данные секции (для записей)
$show_breadcrumbs = $args['show_breadcrumbs'] ?? false; // Хлебные крошки
?>

<header class="page-header <?php echo esc_attr( $modifier ); ?>">

    <!-- Фоновое изображение секции -->
    <div class="page-header__background">

        <!-- Если есть изображение — берем его, если нет — ставим заглушку -->
		<?php if ( $bg_image ) : ?>
		    <?php 
                echo wp_get_attachment_image( $bg_image, 'full', false, [
                    'class' => 'page-header__image',
                    'alt' => esc_attr( $title ),
                    'loading' => 'eager',
                    'fetchpriority' => 'high'
                ] ); 
        ?>

        <?php elseif ( is_singular() && has_post_thumbnail() ) : ?>

            <!-- Текущее изображение записи -->
            <?php
            the_post_thumbnail( 'full', [
                'class'=> 'page-header__image',
                'alt' => esc_attr( $title ),
                'loading' => 'eager',
                'fetchpriority'=>'high'
            ] );
            ?>

        <?php else : ?>

            <!-- Вывод картинки фона-заглушки -->
            <img 
                src="<?php echo esc_url( get_template_directory_uri() . '/public/images/page-header-background.png' ); ?>"
                alt="<?php echo esc_attr( $title ); ?>"
                class="page-header__image"
                loading="eager"
                fetchpriority="high"
            />

        <?php endif; ?>

        <div class="page-header__overlay"></div>

     </div>
 
    <div class="container page-header__container">

        <!-- Контент секции -->
        <div class="page-header__content">

            <!-- Хлебные крошки -->
            <?php if ( $show_breadcrumbs && function_exists( "rank_math_the_breadcrumbs" ) ) : ?>
                <div class="breadcrumbs breadcrumbs--inverse page-header__breadcrumbs">
                    <?php rank_math_the_breadcrumbs(); ?>
                </div>
            <?php endif; ?>           

            <!-- Мета-данные секции (для записей) -->
            <?php if ( $show_meta ) : ?>
                <div class="page-header__meta">
                    <?php 
                        get_template_part( 'template-parts/components/post-meta', null, [
                            'modifier' => 'post-meta--inverse'
                        ] ); 
                    ?>
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

   </div>

    <!-- Кнопка-якорь секции -->
    <?php if ( $scroll_target ) : ?>
        <button 
            type="button" 
            class="page-header__scroll-btn" 
            data-target="<?php echo esc_attr( $scroll_target ); ?>"
            aria-label="Прокрутить к следующей секции"
        >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    <?php endif; ?>

</header>