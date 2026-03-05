<?php 
$content = get_the_content();
    if ( ! empty( trim( $content ) ) ) :
?>
    <!-- Верстка секции Контента -->
    <section class="section section-content">
        <div class="container">
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>