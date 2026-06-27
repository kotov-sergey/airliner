<?php
// Универсальные функции-помощники


// Функция склонения слов после чисел

function my_declension($number, $titles) {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $titles[ ($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)] ];
}

// Функция подсчета времени чтения статьи

function airliner_get_reading_time( $post_id = null ) {
	$post = get_post( $post_id );

	if ( !$post ) {
		return '';
	}
	
	$content = strip_tags( strip_shortcodes( $post->post_content ) );

	$word_count = preg_match_all( '/\S+/u', $content, $matches );

	$words_per_minute = 200;

	$minutes = ceil( $word_count / $words_per_minute );

	if ( $minutes < 1 ) {
		$minutes = 1;
	}

	return $minutes . ' мин';
}

// Функция получения svg иконки для характеристики

function airliner_get_svg( $filename ) {
	$path = get_template_directory() . '/public/icons/' . $filename . '.svg';

	if ( file_exists( $path ) ) {
		return file_get_contents( $path );
	}
	return '';
}

// Функция объединения данных заголовка и переданных аргументов (индекс)

function airliner_prepare_header_args( $acf_field, $template_args ) {
	if ( empty( $acf_field) && !is_array( $acf_field ) ) {
		$acf_field = array();
	}

	$index = $template_args['index'];

	$acf_field['index'] = $index;

	return $acf_field;
}

// Ограничиваем поиск WordPress: ищем ТОЛЬКО по заголовкам (post_title)

function airliner_search_by_title_only( $search, $wp_query ) {
    global $wpdb;

    // Если это не поиск, или поисковая строка пуста, ничего не меняем
    if ( empty( $search ) ) {
        return $search;
    }

    // Получаем слова из поискового запроса
    $q = $wp_query->query_vars;
    $n = ! empty( $q['exact'] ) ? '' : '%';
    $search = '';
    $searchand = '';

    // Формируем новый SQL запрос, который смотрит ТОЛЬКО в post_title
    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( $wpdb->esc_like( $term ) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }

    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        // Убираем из поиска посты с паролем (стандартная защита WP)
        if ( ! is_user_logged_in() ) {
            $search .= " AND ($wpdb->posts.post_password = '') ";
        }
    }

    return $search;
}
// Добавляем фильтр (Приоритет 500 гарантирует, что мы перебьем стандартные фильтры WP)
add_filter( 'posts_search', 'airliner_search_by_title_only', 500, 2 );