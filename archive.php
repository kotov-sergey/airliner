<?php
// Универсальный шаблон для всех типов архивов (Рубрики, Теги, Даты, Авторы)

$archive_title = the_archive_title();

get_header();
?>

<main class="site-main page-archive">
    <div class="container">

        <!-- Шапка страницы архива -->
        <?php
            get_template_part( 'template-parts/components/page-header', null, [
                'title' => $archive_title
            ] );
        ?>

        <?php if ( have_posts() ) : ?>

            <!-- Сетка записей -->
            <div class="l-grid l-grid--3">

                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/components/card-post' ); ?>
                <?php endwhile; ?>

            </div>

            <!-- Пагинация архива -->
            <div class="page-archive__pagination">

                <?php
                    the_posts_pagination( array(
                        'prev_text' => '← Назад',
                        'next_text' => 'Вперед →',
                        'class' => 'pagination'
                    ) );
                ?>
            
            </div>
        
        <?php else : ?>

            <!-- Сообщение для пустого архива -->
            <div class="page-archive__empty">
                <p class="text-muted">В этом разделе пока нет опубликованных статей.</p>
            </div>

        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>