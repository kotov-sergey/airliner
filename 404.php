<?php
// Шаблон страницы ошибки 404 (404.php)

get_header();
?>

<main class="site-main page-404">

    <!-- Hero-секция страницы 404 -->
    <section class="section hero-404">
        <div class="container hero-404__inner">

            <!-- Изображение страницы 404 -->
            <img src="<?php echo esc_url( get_template_directory_uri() . '/public/images/404-placeholder.png' ); ?>" 
                alt="Страница не найдена" 
                class="hero-404__img"
                width="454"
                height="399"
            />

            <!-- Заголовок секции -->
            <h1 class="hero-404__title">404</h1>

            <!-- Второстепенный заголовок -->
            <p class="hero-404__subtitle">Мы сбились с курса</p>

            <!-- Короткое описание секции -->
            <p class="hero-404__text">Страница, к которой вы пытаетесь получить доступ, не существует или была перемещена. Попробуйте вернуться в наш каталог.</p>

            <!-- Кнопки Call to action секции -->
            <a href="<?php echo esc_url( home_url( '/catalog/' ) ); ?>" class="btn btn--primary hero-404__btn">Перейти в каталог</a>

        </div>
    </section>

</main>

<?php get_footer(); ?>