<?php
// AJAX обработчик для фильтров

// Регистрируем хуки (Для авторизованных и неавторизованных юзеров)
add_action('wp_ajax_filter_airliners', 'ajax_filter_airliners_handler');
add_action('wp_ajax_nopriv_filter_airliners', 'ajax_filter_airliners_handler');

function ajax_filter_airliners_handler() {
    
    // Проверяем nonce (защита от хакеров)
    check_ajax_referer('catalog_filter_nonce', 'security');

    // БАЗОВЫЕ АРГУМЕНТЫ
    $args =[
        'post_type'      => 'airliner',
        'posts_per_page' => -1, // Выводим все (или задай лимит 12)
        'tax_query'      => ['relation' => 'AND'],
        'meta_query'     => ['relation' => 'AND']
    ];

    // ЛОВИМ ДАННЫЕ ИЗ ФОРМЫ
    
    // Если выбрали тип фюзеляжа (Таксономия)
    if ( !empty($_POST['fuselage']) ) {
        $args['tax_query'][] =[
            'taxonomy' => 'body-type',
            'field'    => 'term_id',
            'terms'    => $_POST['fuselage'], // Массив выбранных ID
        ];
    }

    // Если ввели минимальную скорость
    if ( !empty($_POST['max_speed']) ) {
        $args['meta_query'][] =[
            'key'     => 'max_speed', // Имя твоего поля скорости в ACF
            'value'   => (int) $_POST['max_speed'],
            'compare' => '>=', // Больше или равно
            'type'    => 'NUMERIC'
        ];
    }

    // ДЕЛАЕМ ЗАПРОС И ВЫВОДИМ ВЕРСТКУ
    $query = new WP_Query($args);

    if ( $query->have_posts() ) {

        echo '<div class="page-catalog__grid">';
            while ( $query->have_posts() ) {
                $query->the_post();
                
                get_template_part('template-parts/components/card-aircraft', null,[
                    'layout' => 'vertical'
                ]);
            }
        echo '</div>';
        
    } else {
        echo '<p class="text-muted">По вашему запросу лайнеров не найдено.</p>';
    }

    wp_reset_postdata();

    // УБИВАЕМ ПРОЦЕСС (Обязательно для AJAX в WP)
    wp_die();
}