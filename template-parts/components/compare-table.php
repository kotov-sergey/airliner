<?php
// Сравнительная таблица авиалайнеров

$compare_query = $args['query'] ?? null;
if ( ! $compare_query || ! $compare_query->have_posts() ) return;

// Данные лучших характеристик авиалайнеров
$winners = $args['winners'] ?? [];

// Заглушка для картинки
$placeholder = get_template_directory_uri() . '/public/images/placeholder-image.svg';
?>

<!-- Сетка сравнения -->
<div class="compare-grid">

    <?php while ( $compare_query->have_posts() ) : $compare_query->the_post(); 
        
        $plane_id = get_the_ID();
        
        // Получение таксономий
        $brands = wp_get_post_terms( $plane_id, 'manufacturer' );
        $body_types = wp_get_post_terms( $plane_id, 'body-type' );
    ?>

        <!-- Класс js-compare-item нужен для удаления через JS -->
        <article class="compare-card js-compare-item">
            
            <!-- Кнопка удаления из сравнения -->
            <button type="button"
                class="compare-card__remove js-compare-remove"
                data-id="<?php echo esc_attr( $plane_id ); ?>"
                title="Удалить из сравнения"
                aria-label="Удалить <?php echo esc_attr( get_the_title() ); ?>">
                &times; 
            </button>

            <!-- Шапка карточки -->
            <header class="compare-card__header">
                
                <div class="compare-card__media">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'medium', [
                            'class' => 'compare-card__image',
                            'loading' => 'lazy'
                            ] ); 
                        ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url( $placeholder ); ?>"
                            alt="<?php echo esc_attr( get_the_title() ); ?>"
                            class="compare-card__image" 
                            loading="lazy" />
                    <?php endif; ?>
                </div>

                <!-- Мета (Пилюли) -->
                <div class="compare-card__meta">
                    <?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
                        <span class="pill pill--solid pill--md"><?php echo esc_html( $brands[0]->name ); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $body_types ) && ! is_wp_error( $body_types ) ) : ?>
                        <span class="pill pill--solid pill--md"><?php echo esc_html( $body_types[0]->name ); ?></span>
                    <?php endif; ?>
                </div>

                <h3 class="compare-card__title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h3>

            </header>

            <!-- Тело карточки (характеристики) -->
            <div class="compare-card__body">

                <!-- Компонент группы характеристик авиалайнера -->  
                <?php
                    get_template_part( 'template-parts/components/specs-group', null, [
                        'plane_id' => $plane_id,
                        'css_mod' => 'compact',
                        'winners' => $winners
                    ] );
                ?>

            </div>

            <!-- Подвал карточки -->
            <div class="compare-card__footer">
                <a href="<?php the_permalink(); ?>" class="btn btn--primary">
                    Страница модели
                </a>
            </div>

        </article>

    <?php endwhile; ?>

    <?php wp_reset_postdata(); ?>
    
</div>