<?php
$gallery = get_field( 'airliner_gallery' );

if ( $gallery && is_array( $gallery ) ) :
    if ( count( $gallery ) >= 10 ) {
        $gallery = array_slice( $gallery, 0, 10 );
    }

$total_images = count( $gallery );
?>
    <!-- Верстка секции Галерея -->
    <section class="section section-gallery section--white">
        <div class="container">

            <!-- Заголовок секции -->
            <?php
            get_template_part( 'template-parts/components/section-header', null, [
                'index' => 2,
                'section_label' => 'Медиа',
                'section_title' => 'Галерея самолета',
                'section_description' => 'Подборка лучших фотографий в высоком разрешении. От аэродинамики фюзеляжа до компоновки кресел.',
                'section_alignment' => 'row',
            ]);
            ?>

            <!-- Сетка галереи (изображений) -->
            <div class="gallery-grid">
                <?php 
                
                foreach ( $gallery as $index => $image ) :

                    $grid_classes = get_gallery_grid_classes( $total_images, $index );
                    $final_class = 'gallery-card glightbox ' . $grid_classes;
                
                    $full_url = $image['url']; // Полный путь к изображению
                    $alt = $image['alt']; // Alt-атрибут
                    $title = $image['title']; //Заголовок
                    $caption = $image['caption']; // Подпись
                    $thumb_url = $image['sizes']['large']; // Размер изображения
                ?>

                    <a href="<?php echo esc_url( $full_url ); ?>"
                       class="<?php echo esc_attr( $final_class ); ?>"
                       data-gallery="plane-gallery"
                       data-title="<?php echo esc_html( $title ); ?>"
                       data-description="<?php echo esc_html( $caption ); ?>"
                    >
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>">

                        <div class="gallery-card__overlay">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                <line x1="11" y1="8" x2="11" y2="14"></line>
                                <line x1="8" y1="11" x2="14" y2="11"></line>
                            </svg>
                        </div>
                    </a>

                <?php endforeach; ?>
            </div>

        </div>
    </section>

<?php endif; ?>