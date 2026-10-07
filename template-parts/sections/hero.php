<?php
// Секция: Hero-баннер (Мост/Проводник)

$hero_title = get_sub_field( 'hero_title' ); // Заголовок секции
$hero_description = get_sub_field( 'hero_description' ); // Описание секции
$hero_background = get_sub_field( 'hero_background' ); // ID вложения изображения

$scroll_target = get_sub_field ( 'hero_target' ); // Кнопка-якорь секции

get_template_part( 'template-parts/components/page-header', null, [
    'title' => $hero_title ? $hero_title : get_the_title(),
    'description' => $hero_description,
    'bg_image' => $hero_background,
    'modifier' => 'page-header--full',
    'scroll_target' => $scroll_target ? $scroll_target : (is_front_page() ? '#brands' : '')
] );