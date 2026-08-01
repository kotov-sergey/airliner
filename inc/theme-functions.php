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

// Подставляем реальный термин таксономии в URL кастомного типа записи
function airliner_custom_post_type_link( $post_link, $post ) {
    
    // Проверяем, что это наш тип записи (airliner) и в ссылке есть наш плейсхолдер
    if ( 'airliner' === $post->post_type && strpos( $post_link, '%manufacturer%' ) !== false ) {
        
        // Получаем термины производителя для этого самолета
        $terms = wp_get_object_terms( $post->ID, 'manufacturer' );

        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            // Если у самолета несколько брендов, берем первый (или основной)
            $term_slug = $terms[0]->slug;
            
            // Заменяем %manufacturer% на реальный слаг (например, 'suhoj')
            $post_link = str_replace( '%manufacturer%', $term_slug, $post_link );
        } else {
            // Если производитель не указан, просто удаляем этот кусок из ссылки
            $post_link = str_replace( '%manufacturer%/', '', $post_link );
        }
    }

    return $post_link;
}
add_filter( 'post_type_link', 'airliner_custom_post_type_link', 1, 2 );

// Восстанавливаем страницу каталога в хлебных крошках Rank Math
add_filter( 'rank_math/frontend/breadcrumb/items', function( $crumbs, $class ) {
    
    // Проверяем: находимся ли мы внутри самолета ИЛИ внутри его таксономий (бренды, типы)
    if ( is_singular( 'airliner' ) || is_tax( ['manufacturer', 'body-type', 'airliner-status', 'country'] ) ) {
        
        // 1. Создаем нашу недостающую крошку
        $custom_crumb = [
            'Авиалайнеры',             // Текст, который будет виден пользователю
            home_url( '/airliners/' ), // Ссылка на твою кастомную страницу-каталог
            'hide_in_schema' => false
        ];

        // 2. Аккуратно разрезаем массив крошек и вставляем нашу на 2-е место
        // (Индекс 0 — это "Главная", значит Индекс 1 — это место сразу после неё)
        array_splice( $crumbs, 1, 0, [ $custom_crumb ] );
    }
    
    return $crumbs;
}, 10, 2 );