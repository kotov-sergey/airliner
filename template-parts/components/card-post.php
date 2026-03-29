<?php
// Карточка статьи
?>

<article class="card-post">

    <a href="<?php echo get_the_permalink(); ?>" class="card-post__link" title="<?php the_title_attribute(); ?>">

        <div class="card-post__media">
            <?php the_post_thumbnail('large', array (
                'class' => 'card-post__image',
                'alt' => $title,
            )); ?>
        </div>

        <div class="card-post__content">

            <div class="card-post__meta">
                <?php get_template_part( 'template-parts/components/post-meta' ); ?>
            </div>

            <h3 class="card-post__title" title="<?php the_title_attribute(); ?>">
                <?php the_title(); ?>
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