<?php
// Карточка статьи

$categories = get_the_category();
$category_name = ! empty( $categories ) ? $categories[0]->name : '';
?>
<article class="card-post">

    <a href="<?php echo get_the_permalink(); ?>" class="card-post__link" title="<?php the_title_attribute(); ?>">

        <div class="card-post__media">
            <?php the_post_thumbnail('large', array (
                'class' => 'card-post__image',
                'alt' => get_the_title(),
            )); ?>
        </div>

        <div class="card-post__content">

            <div class="card-post__meta">
                
                <?php if ( $category_name ) : ?>
                    <span class="card-post__category">
                        <?php echo esc_html( $category_name ); ?>
                    </span>
                <?php endif; ?>

                <time class="card-post__date" datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date('j M, Y'); ?>
                </time>
                
                <span class="card-post__read-time">
                    <?php echo airliner_get_reading_time(); ?>
                </span>
            
            </div>

            <h3 class="card-post__title" title="<?php the_title_attribute(); ?>">
                <?php echo get_the_title(); ?>
            </h3>
            
            <div class="card-post__description">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
            </div>

            <div class="card-post__action">
                <span class="card-post__button">Читать</span>

                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </div>

        </div>
    
    </a>

</article>