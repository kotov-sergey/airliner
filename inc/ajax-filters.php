<?php

// AJAX-обработчики каталога авиалайнеров
add_action( 'wp_ajax_filter_airliners', 'handle_filter_airliners' );
add_action( 'wp_ajax_nopriv_filter_airliners', 'handle_filter_airliners' );


function handle_filter_airliners() {

    // Проверка безопасности AJAX-запроса
    if ( false === check_ajax_referer( 'filter_airliners', 'filter_airliners_nonce', false ) ) {
        wp_send_json_error(
            [
                'message' => 'Ошибка проверки безопасности. Обновите страницу и попробуйте снова.',
            ],
            403
        );
    }

    // Настройки каталога
    $posts_per_page = 9;

    // Разрешённые таксономии
    $allowed_taxonomies = [
        'manufacturer',
        'body-type',
        'airliner-status',
    ];

    // ==========================================
    // 1. ПАГИНАЦИЯ
    // ==========================================

    $paged = isset( $_POST['page'] ) && is_scalar( $_POST['page'] )
        ? max( 1, absint( wp_unslash( $_POST['page'] ) ) )
        : 1;


    // ==========================================
    // 2. ТАКСОНОМИЧЕСКИЕ ФИЛЬТРЫ
    // ==========================================

    $tax_query = [];

    // Функция проверки и получения допустимых ID терминов
    $get_valid_term_ids = static function ( $taxonomy, $raw_ids ) use ( $allowed_taxonomies ) {

        if (
            ! in_array( $taxonomy, $allowed_taxonomies, true )
            || ! taxonomy_exists( $taxonomy )
            || ! is_object_in_taxonomy( 'airliner', $taxonomy )
            || ! is_array( $raw_ids )
        ) {
            return [];
        }

        $raw_ids = wp_unslash( $raw_ids );

        $term_ids = [];

        foreach ( $raw_ids as $raw_id ) {

            if ( ! is_scalar( $raw_id ) ) {
                continue;
            }

            $raw_id = (string) $raw_id;

            if ( ! preg_match( '/^[1-9][0-9]*$/D', $raw_id ) ) {
                continue;
            }

            $term_ids[] = absint( $raw_id );
        }

        $term_ids = array_values( array_unique( $term_ids ) );

        if ( empty( $term_ids ) ) {
            return [];
        }

        // Проверяем существование терминов именно в указанной таксономии
        $valid_ids = get_terms( [
            'taxonomy'   => $taxonomy,
            'include'    => $term_ids,
            'hide_empty' => false,
            'fields'     => 'ids',
        ] );

        if ( is_wp_error( $valid_ids ) || empty( $valid_ids ) ) {
            return [];
        }

        $valid_ids = array_map( 'absint', $valid_ids );

        return array_values( array_intersect( $term_ids, $valid_ids ) );
    };

    // Учитываем текущую страницу таксономии
    $base_taxonomy = '';
    $base_term_id  = 0;

    $has_base_taxonomy = isset( $_POST['base_taxonomy'] );
    $has_base_term     = isset( $_POST['base_term_id'] );

    if ( $has_base_taxonomy || $has_base_term ) {

        if (
            ! $has_base_taxonomy
            || ! $has_base_term
            || ! is_string( $_POST['base_taxonomy'] )
            || ! is_scalar( $_POST['base_term_id'] )
        ) {
            wp_send_json_error(
                [ 'message' => 'Некорректные параметры таксономии.' ],
                400
            );
        }

        $base_taxonomy = sanitize_key(
            wp_unslash( $_POST['base_taxonomy'] )
        );

        $base_term_raw = (string) wp_unslash( $_POST['base_term_id'] );

        $base_term_id = preg_match( '/^[1-9][0-9]*$/D', $base_term_raw )
            ? absint( $base_term_raw )
            : 0;

        // Проверяем допустимость таксономии
        if (
            ! in_array( $base_taxonomy, $allowed_taxonomies, true )
            || ! taxonomy_exists( $base_taxonomy )
            || ! is_object_in_taxonomy( 'airliner', $base_taxonomy )
            || ! $base_term_id
        ) {
            wp_send_json_error(
                [ 'message' => 'Некорректная таксономия или термин.' ],
                400
            );
        }

        // Проверяем существование текущего термина
        $base_term = get_term( $base_term_id, $base_taxonomy );

        if ( ! ( $base_term instanceof WP_Term ) ) {
            wp_send_json_error(
                [ 'message' => 'Указанный термин не найден.' ],
                400
            );
        }

        $tax_query[] = [
            'taxonomy' => $base_taxonomy,
            'field'    => 'term_id',
            'terms'    => [ $base_term_id ],
        ];
    }

    // Фильтр производителей
    $brand_ids = isset( $_POST['brand'] )
        ? $get_valid_term_ids( 'manufacturer', $_POST['brand'] )
        : [];

    if ( ! empty( $brand_ids ) ) {

        $tax_query[] = [
            'taxonomy' => 'manufacturer',
            'field'    => 'term_id',
            'terms'    => $brand_ids,
        ];
    }

    // Фильтр типов фюзеляжа
    $fuselage_ids = isset( $_POST['fuselage'] )
        ? $get_valid_term_ids( 'body-type', $_POST['fuselage'] )
        : [];

    if ( ! empty( $fuselage_ids ) ) {

        $tax_query[] = [
            'taxonomy' => 'body-type',
            'field'    => 'term_id',
            'terms'    => $fuselage_ids,
        ];
    }

    // Фильтр статусов
    $status_ids = isset( $_POST['status'] )
        ? $get_valid_term_ids( 'airliner-status', $_POST['status'] )
        : [];

    if ( ! empty( $status_ids ) ) {

        $tax_query[] = [
            'taxonomy' => 'airliner-status',
            'field'    => 'term_id',
            'terms'    => $status_ids,
        ];
    }


    // Добавляем отношение между группами таксономий
    if ( count( $tax_query ) > 1 ) {
        $tax_query['relation'] = 'AND';
    }

    // ==========================================
    // 3. ЧИСЛОВЫЕ ФИЛЬТРЫ ACF
    // ==========================================

    $meta_query = [];

    // Получение корректного положительного целого числа
    $get_positive_integer = static function ( $key ) {

        if ( ! isset( $_POST[ $key ] ) || ! is_scalar( $_POST[ $key ] ) ) {
            return null;
        }

        $value = trim( (string) wp_unslash( $_POST[ $key ] ) );

        if ( $value === '' || ! preg_match( '/^[0-9]+$/D', $value ) ) {
            return null;
        }

        $value = (int) $value;

        return $value > 0 ? $value : null;
    };

    // Минимальная и максимальная дальность полёта
    $range_min = $get_positive_integer( 'range_min' );
    $range_max = $get_positive_integer( 'range_max' );

    // Проверка корректности диапазона
    if ( null !== $range_min && null !== $range_max && $range_min > $range_max ) {
        wp_send_json_error(
            [ 'message' => 'Некорректный диапазон дальности.' ],
            400
        );
    }

    if ( null !== $range_min ) {

        $meta_query[] = [
            'key'     => 'specs_performance_range',
            'value'   => $range_min,
            'type'    => 'NUMERIC',
            'compare' => '>=',
        ];
    }

    if ( null !== $range_max ) {

        $meta_query[] = [
            'key'     => 'specs_performance_range',
            'value'   => $range_max,
            'type'    => 'NUMERIC',
            'compare' => '<=',
        ];
    }

    // Минимальная и максимальная вместимость
    $passengers_min = $get_positive_integer( 'passengers_min' );
    $passengers_max = $get_positive_integer( 'passengers_max' );

    // Проверка корректности диапазона
    if ( null !== $passengers_min && null !== $passengers_max && $passengers_min > $passengers_max ) {
        wp_send_json_error(
            [ 'message' => 'Некорректный диапазон вместимости.' ],
            400
        );
    }

    if ( null !== $passengers_min ) {

        $meta_query[] = [
            'key'     => 'specs_weight_passengers',
            'value'   => $passengers_min,
            'type'    => 'NUMERIC',
            'compare' => '>=',
        ];
    }

    if ( null !== $passengers_max ) {

        $meta_query[] = [
            'key'     => 'specs_weight_passengers',
            'value'   => $passengers_max,
            'type'    => 'NUMERIC',
            'compare' => '<=',
        ];
    }


    if ( count( $meta_query ) > 1 ) {
        $meta_query['relation'] = 'AND';
    }

    // ==========================================
    // 4. БАЗОВЫЕ ПАРАМЕТРЫ ЗАПРОСА
    // ==========================================

    $args = [
        'post_type'           => 'airliner',
        'post_status'         => 'publish',
        'posts_per_page'      => $posts_per_page,
        'paged'               => $paged,
        'ignore_sticky_posts' => true,
    ];

    if ( ! empty( $tax_query ) ) {
        $args['tax_query'] = $tax_query;
    }

    if ( ! empty( $meta_query ) ) {
        $args['meta_query'] = $meta_query;
    }

    // ==========================================
    // 5. ПОИСК ПО НАЗВАНИЮ МОДЕЛИ
    // ==========================================

    $search = '';

    if ( isset( $_POST['search'] ) && is_string( $_POST['search'] ) ) {

        $search = sanitize_text_field(
            wp_unslash( $_POST['search'] )
        );

        if ( $search !== '' ) {
            $args['s'] = $search;
        }
    }

    // ==========================================
    // 6. СОРТИРОВКА
    // ==========================================

    $allowed_sorts = [
        'default',
        'range_desc',
        'capacity_desc',
        'name_asc',
        'year_desc',
    ];

    $sort = isset( $_POST['sort'] ) && is_string( $_POST['sort'] )
        ? sanitize_key( wp_unslash( $_POST['sort'] ) )
        : 'default';

    if ( ! in_array( $sort, $allowed_sorts, true ) ) {
        $sort = 'default';
    }

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

            // Сортировка по дате добавления записи
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';

            break;


        case 'default':
        default:

            // Текущий вариант сортировки по menu_order и дате
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';

            break;
    }

    // ==========================================
    // 7. ВЫБОР ВИДА ОТОБРАЖЕНИЯ
    // ==========================================

    $view = isset( $_POST['view'] ) && is_string( $_POST['view'] )
        ? sanitize_key( wp_unslash( $_POST['view'] ) )
        : 'grid';

    $allowed_views = [
        'grid',
        'table',
    ];

    if ( ! in_array( $view, $allowed_views, true ) ) {
        $view = 'grid';
    }

    // ==========================================
    // 8. ВЫПОЛНЕНИЕ ЗАПРОСА
    // ==========================================

    $query = new WP_Query( $args );

    // ==========================================
    // 9. ФОРМИРОВАНИЕ HTML КАРТОЧЕК
    // ==========================================

    ob_start();

    if ( $query->have_posts() ) {

        while ( $query->have_posts() ) {

            $query->the_post();

            get_template_part(
                'template-parts/components/cards/card-aircraft',
                null,
                [
                    'view' => $view,
                ]
            );
        }

    } else {

        echo '<div class="catalog-empty catalog-layout__empty">';
        echo '<p>Лайнеров по заданным параметрам не найдено.</p>';
        echo '</div>';
    }

    wp_reset_postdata();

    $html = ob_get_clean();

    // ==========================================
    // 10. JSON-ОТВЕТ
    // ==========================================

    wp_send_json_success( [
        'html'        => $html,
        'count'       => number_format_i18n( $query->found_posts ),
        'max_pages'   => (int) $query->max_num_pages,
        'currentPage' => $paged,
    ] );
}