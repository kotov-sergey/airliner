<?php
// Секция: Hero-баннер (Мост/Проводник)

$hero_title = get_sub_field( 'hero_title' ); // Заголовок секции
$hero_description = get_sub_field( 'hero_description' ); // Описание секции
$hero_background = get_sub_field( 'hero_background' ); // ID вложения изображения

$modifier = get_sub_field( 'hero_modifier' ); // Модификатор секции
$scroll_target = get_sub_field ( 'hero_target' ); // Кнопка-якорь секции

get_template_part( 'template-parts/components/hero', null, [
    'title' => $hero_title ? $hero_title : get_the_title(),
    'description' => $hero_description,
    'background_image' => $hero_background,
    'modifier' => $modifier ? $modifier : 'hero--default',
    'scroll_target' => $scroll_target ? $scroll_target : (is_front_page() ? '#brands' : '')
] );