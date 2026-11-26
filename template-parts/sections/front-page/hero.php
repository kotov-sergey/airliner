<?php
// Секция: Hero-баннер (Мост/Проводник)

$hero_title = get_sub_field( 'hero_title' ); // Заголовок секции
$hero_description = get_sub_field( 'hero_description' ); // Описание секции
$hero_background = get_sub_field( 'hero_background' ); // ID вложения изображения

$scroll_target = get_sub_field ( 'hero_target' ); // Кнопка-якорь секции

$cta_button = get_sub_field( 'cta_button' ); // Кнопка CTA секции

// Параметры CTA-кнопки по умолчанию
$cta_text = '';
$cta_url = '';
$cta_target = '';

// Получение данных CTA-кнопки
if ( is_array( $cta_button) && ! is_wp_error( $cta_button ) ) {
    $cta_text = $cta_button['title'] ?? 'Перейти в каталог';
    $cta_url = $cta_button['url'] ?? home_url( '/airliners/' );
    $cta_target = $cta_button['target'] ?? '';
}

get_template_part( 'template-parts/components/pages/page-header', null, [
    'title' => $hero_title ? $hero_title : get_the_title(),
    'description' => $hero_description,
    'bg_image' => $hero_background,

    'modifier' => 'page-header--full',
    'show_meta' => false,
    'show_breadcrumbs' => false,

    'scroll_target' => $scroll_target ? $scroll_target : (is_front_page() ? '#brands' : ''),
    'cta_text' => $cta_text,
    'cta_url' => $cta_url,
    'cta_target' => $cta_target,
] );