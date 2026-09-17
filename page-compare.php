<?php
/* Template Name: Сравнение авиалайнеров */
get_header();

// Получение ID самолетов из адресной строки
$ids_param = isset($_GET['ids']) ? sanitize_text_field($_GET['ids']) : '';
$plane_ids = !empty($ids_param) ? array_map('intval', explode(',', $ids_param)) : [];

// Ограничение максимального кол-ва авиалайнеров
$plane_ids = array_slice($plane_ids, 0, 4);
?>

<main class="site-main page-compare">

    <!-- Hero-секция -->
    <?php
        get_template_part( 'template-parts/components/page-header', null, [
            'title' => 'Сравнение авиалайнеров',
            'description' => 'Сравнивайте характеристики и значения, выявляющие лучшие авиалайнеры в мире!'
        ] );
    ?>

    <div class="container">

        <?php if( count($plane_ids) >=2 ) : ?>

            <!-- Запрос данных авиалайнеров -->
            <?php 
            $compare_query = new WP_Query( [
                'post_type' => 'airliner',
                'post__in' => $plane_ids,
                'orderby' => 'post__in',
                'posts_per_page' => 4
            ] );
            ?>

            <!-- Проверяем, что в базе действительно нашлось как минимум 2 самолета -->
            <?php if ( $compare_query->have_posts() && $compare_query->found_posts >= 2 ) : ?>
                
                <!-- Таблица сравнения авиалайнеров -->
                <?php 
                    get_template_part( 'template-parts/components/compare-table', null, [
                        'query' => $compare_query
                    ] );
                ?>

                <?php wp_reset_postdata(); ?>

            <?php else : ?>

                <!-- Заглушка, если по переданным ID самолеты не найдены в базе -->
                <div class="compare-empty text-center">
                    <p class="text-secondary mb-4">Выбранные самолеты не найдены в базе данных.</p>
                    <a href="<?php echo esc_url( home_url( '/airliners/' ) ); ?>" class="btn btn--primary">Перейти в каталог</a>
                </div>
            
            <?php endif; ?>

        <?php else : ?>

            <!-- Заглушка, если выбрано меньше 2 самолетов -->
            <div class="compare-empty">
                <p class="text-secondary">Для сравнения выберите как минимум 2 самолета из каталога.</p>
                <a href="<?php echo esc_url( home_url( '/airliners/' ) ); ?>" class="btn btn--primary">Перейти в каталог</a>
            </div>

        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>