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