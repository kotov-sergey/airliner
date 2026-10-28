<?php
add_action( 'wp_ajax_filter_airliners', 'handle_filter_airliners' );
add_action( 'wp_ajax_nopriv_filter_airliners', 'handle_filter_airliners' );

function handle_filter_airliners() {
    $tax_query  = [];
    $meta_query = [];

    $paged = ! empty( $_POST['page'] ) ? (int) $_POST['page'] : 1;
    $posts_per_page = 9; // Должно совпадать с первичным выводом!

    // 1. Учет архива таксономии
    if ( ! empty( $_POST['base_taxonomy'] ) && ! empty( $_POST['base_term_id'] ) ) {
        $tax_query[] = [
            'taxonomy' => sanitize_text_field( $_POST['base_taxonomy'] ),
            'field'    => 'term_id',
            'terms'    => (int) $_POST['base_term_id'],
        ];
    }

    // 2. Чекбоксы таксономий из сайдбара
    if ( ! empty( $_POST['brand'] ) && is_array( $_POST['brand'] ) ) {
        $tax_query[] = [
            'taxonomy' => 'manufacturer',
            'field'    => 'term_id',
            'terms'    => array_map( 'intval', $_POST['brand'] ),
        ];
    }

    if ( ! empty( $_POST['fuselage'] ) && is_array( $_POST['fuselage'] ) ) {
        $tax_query[] = [
            'taxonomy' => 'body-type',
            'field'    => 'term_id',
            'terms'    => array_map( 'intval', $_POST['fuselage'] ),
        ];
    }

    if ( ! empty( $_POST['status'] ) && is_array( $_POST['status'] ) ) {
        $tax_query[] = [
            'taxonomy' => 'airliner-status',
            'field'    => 'term_id',
            'terms'    => array_map( 'intval', $_POST['status'] ),
        ];
    }

    // 3. Числовые фильтры из сайдбара (УЧИТЫВАЕМ ГРУППЫ ACF)
    if ( ! empty( $_POST['range'] ) ) {
        $meta_query['filter_range'] = [
            'key'     => 'specs_performance_range', // Реальное имя в БД
            'value'   => (int) $_POST['range'],
            'type'    => 'NUMERIC',
            'compare' => '>=',
        ];
    }

    if ( ! empty( $_POST['passengers'] ) ) {
        $meta_query['filter_passengers'] = [
            'key'     => 'specs_weight_passengers', // Реальное имя в БД
            'value'   => (int) $_POST['passengers'],
            'type'    => 'NUMERIC',
            'compare' => '>=',
        ];
    }

    // Базовые параметры запроса
    $args = [
        'post_type'      => 'airliner',
        'post_status'    => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'tax_query'      => $tax_query,
        'meta_query'     => $meta_query 
    ];

    // Поиск по названию модели
    if ( ! empty( $_POST['search'] ) ) {
        $args['s'] = sanitize_text_field( $_POST['search'] );
    }

    // 4. СОРТИРОВКА (УЧИТЫВАЕМ ГРУППЫ ACF)
    $sort = ! empty( $_POST['sort'] ) ? sanitize_text_field( $_POST['sort'] ) : 'popular';

    switch ( $sort ) {
        case 'range_desc':
            $args['meta_key'] = 'specs_performance_range';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        case 'capacity_desc':
            $args['meta_key'] = 'specs_weight_passengers';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        case 'name_asc':
            $args['orderby'] = 'title';
            $args['order']   = 'ASC';
            break;

        case 'year_desc':
            // Сортировка по дате публикации (или замени на ACF поле года)
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;

        case 'popular':
        default:
            $args['orderby'] = 'menu_order date';
            $args['order']   = 'DESC';
            break;
    }

    // Добавляем таксономии, если они выбраны
    if ( ! empty( $tax_query ) ) {
        $tax_query['relation'] = 'AND';
        $args['tax_query']     = $tax_query;
    }

    // Добавляем мета-поля, если они выбраны (или участвуют в сортировке)
    if ( ! empty( $meta_query ) ) {
        $meta_query['relation'] = 'AND';
        $args['meta_query']     = $meta_query;
    }

    $query = new WP_Query( $args );

    $view = ! empty( $_POST['view'] ) ? sanitize_text_field( $_POST['view'] ) : 'grid';
    
    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/components/card-aircraft', null, [
                'view' => $view
            ] );
        }
        wp_reset_postdata();
    } else {
        echo '<div class="catalog-empty"><p>Лайнеров по заданным параметрам не найдено.</p></div>';
    }

    $html = ob_get_clean();

    wp_send_json_success([
        'html'  => $html,
        'count' => $query->found_posts,
        'max_pages' => $query->max_num_pages,
        'currentPage' => $paged
    ]);
}