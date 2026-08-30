<?php
// Секция: Часто задаваемые вопросы (FAQ)

$section_index = $args['index'] ?? '';
$section_header = get_sub_field( 'section_header' );

$section_background = get_sub_field( 'section_background' );

if ( $section_background === 'default' || ! $section_background ) {
	$section_background = '';
}

$classes = trim( 'section faq-block ' . $section_background );

$accordion = get_sub_field( 'accordion' );

if ( ! $section_header && ! $accordion ) return;
?>

<section class="<?php echo esc_attr( $classes ); ?>">
    <div class="container">

        <!-- Блок заголовка секции -->
        <?php 
            get_template_part( 'template-parts/components/section-header', null, [
                'data' => $section_header,
                'number' => $section_index
            ]);
        ?>

        <!-- Блок вопрос-ответ секции -->
        <?php if ( have_rows( 'accordion' ) ) : ?>
            <div class="accordion l-grid l-grid--2 faq-block__accordion">

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