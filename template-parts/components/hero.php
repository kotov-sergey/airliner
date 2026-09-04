<?php
// Верстка Hero-секции 

$title = $args['title'] ?? get_the_title(); // Заголовок секции
$description = $args['description'] ?? ''; // Описание секции
$bg_image = $args['background_image'] ?? ''; // ID вложения изображения

$modifier = $args['modifier'] ?? 'hero--default'; // Модификатор секции
$scroll_target = $args['scroll_target'] ?? ''; // Кнопка-якорь секции

$show_meta = $args['show_meta'] ?? false; // Мета-данные секции (для записей)
$show_breadcrumbs = $args['show_breadcrumbs'] ?? false; // Хлебные крошки
?>

<!-- Hero-секция -->
<header class="hero <?php echo esc_attr( $modifier ); ?>">
	<div class="hero__background">
		
        <!-- Фоновое изображение секции-->
		<?php if ( $bg_image ) : ?>
		    <?php 
                echo wp_get_attachment_image( $bg_image, 'full', false, [
                    'class' => 'hero__image',
                    'loading' => 'eager',
                    'fetchpriority' => 'high'
                ] ); 
            ?>
        <?php else : ?>
            
            <!-- Заглушка: миниатюра текущей страницы -->
            <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'full', [
                        'class' => 'hero__image',
                        'loading' => 'eager',
                        'fetchpriority' => 'high'
                    ] );
                }
            ?>
		<?php endif; ?>

        <!-- Затемнение фона секции -->
		<div class="hero__overlay"></div>
	</div>

	<div class="container hero__container">
		<div class="hero__content">	

            <!-- Хлебные крошки -->
            <?php if ( $show_breadcrumbs && function_exists( "rank_math_the_breadcrumbs" ) ) : ?>
                <div class="breadcrumbs breadcrumbs--inverse hero__breadcrumbs">
                    <?php rank_math_the_breadcrumbs(); ?>
                </div>
            <?php endif; ?>           

            <!-- Мета-данные секции (для записей) -->
            <?php if ( $show_meta ) : ?>
                <div class="hero__meta">
                    <?php 
                        get_template_part( 'template-parts/components/post-meta', null, [
                            'modifier' => 'post-meta--inverse'
                        ] ); 
                    ?>
                </div>
            <?php endif; ?>

            <!-- Заголовок секции -->
			<?php if ( $title ): ?>
				<h1 class="hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>
			
            <!-- Описание секции -->
			<?php if ( $description ): ?>
				<div class="hero__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
		
		</div>
	</div>

    <!-- Кнопка-якорь секции -->
    <?php if ( $scroll_target ) : ?>
        <button type="button" class="hero__scroll-btn" data-target="<?php echo esc_attr( $scroll_target ); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    <?php endif; ?>

</header>