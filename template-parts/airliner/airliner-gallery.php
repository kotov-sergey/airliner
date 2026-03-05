<?php
$gallery = get_field( 'airliner-gallery' );

if ( $gallery ) :
?>
    <!-- Верстка секции Галерея -->
    <section class="section section-gallery">
        <div class="container">

            <div class="gallery-grid">
                <?php foreach ( $gallery as $image ) :
                $full_url = $image['url']; // Полный путь к изображению
                $alt = $image['alt']; // Alt-атрибут
                $title = $image['title']; //Заголовок
                $caption = $image['caption']; // Подпись

                $thumb_url = $image['sizes']['large'];
                ?>

                    <a href=""
                       class="gallery-card glightbox"
                       data-gallery="plane-gallery"
                       data-title=""
                       data-description=""
                    >

                        <div class="gallery-card__image">
                            <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($alt); ?>">
                        
                            <div class="gallery-card__overlay">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                        </div>

                        <?php if($caption): ?>
                            <div class="gallery-card__caption">
                                <?php echo esc_html($caption); ?>
                            </div>
                        <?php endif; ?>

                    </a>

                <?php endforeach; ?>
            </div>

        </div>
    </section>

<?php endif; ?>