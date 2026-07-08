<?php
// Секция: Часто задаваемые вопросы (FAQ)

$section_title = get_sub_field( 'section_title' );
$section_subtitle = get_sub_field( 'section_subtitle' );
$section_background = get_sub_field( 'section_background' ) ?: 'section--gray';

$accordion = get_sub_field( 'accordion' );

if ( ! $section_title && ! $accordion ) return;
?>

<section class="section faq-block <?php echo esc_attr( $section_background ); ?>">
    <div class="container">

        <!-- Блок заголовка секции -->
        <div class="faq-block__header">

            <?php if ( $section_title ) : ?>
                <h2 class="faq-block__title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>

            <?php if ( $section_subtitle ) : ?>
                <p class="faq-block__subtitle"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        
        </div>

        <!-- Блок вопрос-ответ секции -->
        <?php if ( have_rows( 'accordion' ) ) : ?>
            <div class="accordion">

                <?php while ( have_rows( 'accordion' ) ) : the_row(); 
                
                    $accordion_question = get_sub_field( 'accordion_question' );
                    $accordion_answer = get_sub_field( 'accordion_answer' );
                
                    if ( ! $accordion_question || ! $accordion_answer ) continue;
                ?>

                    <details class="accordion__item">

                        <summary class="accordion__trigger">
                            <span class="accordion__title"><?php echo esc_html( $accordion_question ); ?></span>
                        </summary>

                        <div class="accordion__content">
                            <?php echo wp_kses_post( wpautop( $accordion_answer ) ); ?>
                        </div>

                    </details>

                <?php endwhile; ?>

            </div>
        <?php endif; ?>

    </div>
</section>