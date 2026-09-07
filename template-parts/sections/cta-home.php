<?php 
// Секция призыва CTA на главной

$section_image = get_sub_field( 'section_background' ); // Фоновое изображение секции

$section_title = get_sub_field( 'section_title' ); // Заголовок секции
$section_description = get_sub_field( 'section_description' ); // Описание секции

$section_button = get_sub_field( 'section_button' ); // Кнопка секции

if ( ! $section_title && ! $section_description ) return;
?>

<!-- Секция призыва к действию -->
<section class="section cta-home">

    <div class="cta-home__background">

        <?php if ( $section_image ) : ?>

            <!-- Фоновое изображение секции -->
            <?php
                echo wp_get_attachment_image( $section_image['id'], 'full', false, array(
                    'class' => 'cta-home__image',
                    'loading' => 'lazy',
                ) );
            ?>

            <?php else : ?>

            <!-- Фоновое изображение-заглушка -->
            <img 
                src="<?php echo esc_url( get_template_directory_uri() . '/public/images/cta-home-bg.webp' ); ?>" 
                class="cta-home__image" 
                alt="Фон призыва к действию"
                loading="lazy" 
            />

        <?php endif; ?>

        <!-- Затемнение фонового изображения секции -->
        <div class="cta-home__overlay"></div>

    </div>

    <div class="container cta-home__inner">

        <!-- Общий контент секции -->
        <div class="cta-home__content">

            <!-- Заголовок секции -->
            <?php if ( $section_title ) : ?>
                <h2 class="cta-home__title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>

            <!-- Описание секции -->
            <?php if ( $section_description ) : ?>
                <div class="cta-home__description">
                    <?php echo wp_kses_post( wpautop( $section_description ) ); ?>
                </div>
            <?php endif; ?>

            <!-- Кнопка секции -->
            <?php if ( $section_button && is_array( $section_button ) ) : 
            
                $btn_target = ! empty ($section_button['target']) ? $section_button['target'] : '_self';
            ?>
                <a class="btn btn--primary cta-home__btn"
                    href="<?php echo esc_url( $section_button['url']); ?>" 
                    target="<?php echo esc_attr( $btn_target ); ?>">
                        <?php echo esc_html( $section_button['title'] ); ?>
                </a>
            <?php endif; ?>

        </div>

    </div>

</section>