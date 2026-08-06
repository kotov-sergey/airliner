<?php
// Главный резервный шаблон темы (index.php)

get_header();
?>

<main class="site-main page-index">
    <div class="container">

        <!-- Заголовок страницы -->
        <header class="page-index__header">
            <h1 class="page-index__title">
                
                <?php
                    if ( is_home() && ! is_front_page() ) {
                        single_post_title();
                    }
                    elseif ( is_archive() ) {
                        the_archive_title();
                    }
                    else {
                        echo esc_html( 'Блог и публикации' );
                    }
                ?>
                
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>

            <!-- Сетка записей -->
            <div class="l-grid l-grid--3">

                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/components/card-post' ); ?>
                <?php endwhile; ?>

            </div>

            <!-- Пагинация (Номера страниц) -->
            <div class="page-index__pagination">

                <?php
                    the_posts_pagination( array(
                        'prev_text' => '← Назад',
                        'next_text' => 'Вперед →',
                        'class' => 'pagination'
                    ) );
                ?>
            
            </div>
        
        <?php else : ?>

            <!-- Сообщение (Если нет записей) -->
            <div class="page-index__empty">
                <p class="text-muted">Извините, на данный момент публикаций не найдено.</p>
            </div>

        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>