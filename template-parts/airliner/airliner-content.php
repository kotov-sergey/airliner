<?php 
$content = get_the_content();
    if ( ! empty( trim( $content ) ) ) :
?>
    <!-- Верстка секции Контента -->
    <section class="section section-content section--white">
        <div class="container container--narrow">
  
            <!-- Заголовок секции -->
            <?php
            get_template_part( 'template-parts/components/section-header', null, [
                'index' => 4,
                'section_label' => 'История',
                'section_title' => 'История самолета',
                'section_description' => 'Познакомьтесь с конструктивными особенностями, инновациями в аэродинамике и уровнем комфорта, которые делают этот лайнер уникальным.',
                'section_alignment' => 'column',
            ]);
            ?>

            <!-- Контент записи -->
            <div class="entry-content">
                <?php the_content(); ?>
            </div>

        </div>
    </section>
<?php endif; ?>