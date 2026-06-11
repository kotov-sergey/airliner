<?php
// AJAX обработчик для фильтров

// Регистрируем хуки (Для авторизованных и неавторизованных юзеров)
add_action('wp_ajax_filter_airliners', 'ajax_filter_airliners_handler');
add_action('wp_ajax_nopriv_filter_airliners', 'ajax_filter_airliners_handler');

function ajax_filter_airliners_handler() {
    
    // Проверяем nonce (защита от хакеров)
    check_ajax_referer('catalog_filter_nonce', 'security');

    // ПОЛУЧАЕМ НОМЕР СТРАНИЦЫ ИЗ JS
    $paged = isset($_POST['paged']) ? absint($_POST['paged']) : 1;
    
    // БАЗОВЫЕ АРГУМЕНТЫ
    $args =[
        'post_type'      => 'airliner',
        'posts_per_page' => 9, // Выводим все (или задай лимит 12)
        'paged' => $paged,
        'tax_query'      => ['relation' => 'AND'],
        'meta_query'     => ['relation' => 'AND']
    ];

    // ЛОВИМ ДАННЫЕ ИЗ ФОРМЫ
    
    // Если выбрали производителя (Таксономия)
    if ( !empty($_POST['brand']) ) {
        $args['tax_query'][] =[
            'taxonomy' => 'manufacturer',
            'field'    => 'term_id',
            'terms'    => $_POST['brand'], // Массив выбранных ID
        ];
    }  

    // Если выбрали тип фюзеляжа (Таксономия)
    if ( !empty($_POST['fuselage']) ) {
        $args['tax_query'][] =[
            'taxonomy' => 'body-type',
            'field'    => 'term_id',
            'terms'    => $_POST['fuselage'], // Массив выбранных ID
        ];
    }

    // Если выбрали статус лайнера (Таксономия)
    if ( !empty($_POST['status']) ) {
        $args['tax_query'][] =[
            'taxonomy' => 'airliner-status',
            'field'    => 'term_id',
            'terms'    => $_POST['status'], // Массив выбранных ID
        ];
    }   

    // Если ввели дальность полёта
    if ( !empty($_POST['range']) ) {
        $args['meta_query'][] =[
            'key'     => 'specs_performance_range', // Имя твоего поля скорости в ACF
            'value'   => (int) $_POST['range'],
            'compare' => '>=', // Меньше или равно
            'type'    => 'NUMERIC'
        ];
    }

    // Если ввели вместимость
    if ( !empty($_POST['passengers']) ) {
        $args['meta_query'][] =[
            'key'     => 'specs_weight_passengers', // Имя твоего поля скорости в ACF
            'value'   => (int) $_POST['passengers'],
            'compare' => '>=', // Меньше или равно
            'type'    => 'NUMERIC'
        ];
    }

    // ДЕЛАЕМ ЗАПРОС И ВЫВОДИМ ВЕРСТКУ
    $query = new WP_Query($args);

    if ( $query->have_posts() ) {

        echo '<div class="l-grid l-grid--3 page-catalog__grid">';
            while ( $query->have_posts() ) {
                $query->the_post();
                
                get_template_part('template-parts/components/card-aircraft', null,[
                    'layout' => 'vertical'
                ]);
            }
        echo '</div>';

        if ( $query->max_num_pages > 1 ) {
            
            $current_page = isset($_POST['paged']) ? absint($_POST['paged']) : 1;

            echo '<div class="page-catalog__pagination">';
            echo paginate_links( array(
                'total'     => $query->max_num_pages,
                'current'   => $current_page,
                'prev_text' => '&larr; Назад',
                'next_text' => 'Вперёд &rarr;',
            ) );
            echo '</div>';
        }

    } else {
        echo '<p class="text-muted">По вашему запросу лайнеров не найдено.</p>';
    }

    wp_reset_postdata();

    // УБИВАЕМ ПРОЦЕСС (Обязательно для AJAX в WP)
    wp_die();
}