<?php
$gallery = get_field( 'airliner_gallery' );

if ( $gallery ) :
?>
    <!-- Верстка секции Галерея -->
    <section class="section section-gallery section--white">
        <div class="container">

            <h2>Галерея</h2>

            <div class="gallery-grid">
                <?php foreach ( $gallery as $image ) :
                    $full_url = $image['url']; // Полный путь к изображению
                    
                    $alt = $image['alt']; // Alt-атрибут
                    $title = $image['title']; //Заголовок
                    $caption = $image['caption']; // Подпись

                    $thumb_url = $image['sizes']['large'];
                ?>

                    <a href="<?php echo esc_url( $full_url ); ?>"
                       class="gallery-card glightbox"
                       data-gallery="plane-gallery"
                       data-title="<?php echo esc_html( $title ); ?>"
                       data-description="<?php echo esc_html( $caption ); ?>"
                    >
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
                    </a>

                <?php endforeach; ?>
            </div>

        </div>
    </section>

<?php endif; ?>