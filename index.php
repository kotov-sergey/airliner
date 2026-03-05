<?php
/*
 * Главный шаблон-заглушка.
 * Обрабатывает Поиск, Архивы, Теги, если для них нет своих файлов.
 */
get_header();
?>

<main class="main">
    <div class="container">

        <header class="page-header">
            <h1 class="page-title">
                <?php
                	if ( is_search() ) {
                		printf( 'Результаты поиска: %s', get_search_query() );
                	} elseif ( is_archive() ) {
                		the_archive_title();
                	} else {
                		_e( 'Блог', 'avialiner' );
                	}
                ?>
            </h1>
        </header>

        <!-- Сетка постов -->
        <?php if ( have_posts() ) : ?>
        	<div class="posts-grid">
            	<?php while ( have_posts() ) : the_post(); ?>

            	<!-- Карточка новости -->
            	<?php get_template_part( 'template-parts/card', 'post' ); ?>

            	<?php endwhile; ?>
        	</div>

        	<!-- Пагинация -->
        	<?php the_posts_pagination(); ?>

        <?php else : ?>
        	<p>Ничего не найдено.</p>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
