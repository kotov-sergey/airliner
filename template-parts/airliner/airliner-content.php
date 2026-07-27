<?php 
// Верстка секции контента страницы авиалайнера

$content = get_the_content();
    if ( ! empty( trim( $content ) ) ) :
?>
    <!-- Верстка секции Контента -->
    <section class="section section-content section--white">
        <div class="container container--narrow">
  
            <!-- Заголовок секции -->
            <?php
                get_template_part( 'template-parts/components/section-header', null, [
                    'number' => '04',
                    'data' => [
                        'header_label' => 'История',
                        'header_title' => 'История самолета',
                        'header_description' => 'Познакомьтесь с конструктивными особенностями, инновациями в аэродинамике и уровнем комфорта, которые делают этот лайнер уникальным.',                    
                    ]
                ]);
            ?>

            <!-- Контент записи -->
            <div class="entry-content">
                <?php the_content(); ?>
            </div>

        </div>
    </section>
<?php endif; ?>