<?php
// Логика получения данных для Авиалайнеров


// Функция получения массива производителей (брендов)
function airliner_get_all_brands( $limit = 0 ) {
	$brands = [];
	
	$terms = get_terms( [
		'taxonomy' => 'manufacturer',
		'order' => 'ASC',
		'hide_empty' => false,
		'number' => 0
	] );

	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			if ( $term->parent == 0 ) {
				continue;
			}
			
			$link = get_term_link( $term );
			
			if ( !is_wp_error( $link ) ) {
				$term->link = $link;
			}
			else {
				$term->link = '#';
			}
			
			$brands[] = $term;
			
			if ( $limit > 0 && count( $brands ) >= $limit ) {
				break;
			}
		}
	}
	
	return $brands;
}

//Получение списка всех терминов для мета-данных карточки
function airliner_get_meta_terms( $post_id, $taxonomies = array() ) {
	$meta_values = array();

	foreach ( $taxonomies as $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );

		if ( !empty( $terms ) || !is_wp_error( $terms ) ) {
			$term = $terms[0];

			$meta_values[$taxonomy] = array(
				'id'=>$term->term_id,
				'slug'=>$term->slug,
				'name'=>$term->name,
				'link'=>get_term_link( $term ),	
			);
		}
	}
	return $meta_values;
}

// Функция получения имени и логотипа производителя
function airliner_get_brand_data( $post_id ) {
	$brands = get_the_terms( $post_id, 'manufacturer' );

	if ( $brands && !is_wp_error( $brands) ) {
		$main_brand = $brands[0];
		$brand_logo = get_field( 'brand_logo', $main_brand );
		$brand_link = get_term_link( $main_brand );
		
		if ( is_wp_error( $brand_link ) ) {
            $brand_link = '#';
        }
		
		return [
            'name' => $main_brand->name,
            'logo' => $brand_logo ? $brand_logo['url'] : false,
			'url' => $brand_link,
        ];
	}
	return false;
}

// Функция получения и обработки характеристик авиалайнера
function get_airliner_specs_config() {
	return [

		// Группа 1: Размеры
		'specs_dimensions' => [
			'label' => 'Размеры',
			'fields' => [
				'length' => ['label' => 'Длина', 'unit' => 'м', 'icon' => 'specs/ruler', 'better' => 'max'],
				'wingspan' => ['label' => 'Размах крыла', 'unit' => 'м', 'icon' => 'specs/wings', 'better' => 'max'],
				'height' => ['label' => 'Высота', 'unit' => 'м', 'icon' => 'specs/height', 'better' => 'max'],
				'area' => ['label' => 'Площадь крыла', 'unit' => 'м²', 'icon' => 'specs/area', 'better' => 'max'],
			]
		],

		// Группа 2: Производительность
		'specs_performance' => [
			'label' => 'Производительность',
			'fields' => [
				'cruise_speed' => ['label' => 'Крейсерская скорость', 'unit' => 'Mach', 'icon' => 'specs/speed', 'better' => 'max'],
				'max_speed' => ['label' => 'Макс. скорость', 'unit' => 'Mach', 'icon' => 'specs/speed-max', 'is_key' => true, 'better' => 'max'],
				'range' => ['label' => 'Дальность полёта', 'unit' => 'км', 'icon' => 'specs/distance', 'is_key' => true, 'better' => 'max'],
				'ceiling' => ['label' => 'Практический потолок', 'unit' => 'м', 'icon' => 'specs/altitude', 'better' => 'max'],
			]
		],

		// Группа 3: Масса и загрузка
		'specs_weight' => [
			'label' => 'Масса и загрузка',
			'fields' => [
				'mtow' => ['label' => 'Макс. взлётная масса', 'unit' => 'кг', 'icon' => 'specs/weight', 'better' => 'min'],
				'empty' => ['label' => 'Масса пустого', 'unit' => 'кг', 'icon' => 'specs/weight-light', 'better' => 'min'],
				'fuel' => ['label' => 'Ёмкость топлива', 'unit' => 'л', 'icon' => 'specs/fuel', 'better' => 'max'],
				'passengers' => ['label' => 'Вместимость', 'unit' => 'чел.', 'icon' => 'specs/seats', 'is_key' => true, 'better' => 'max'],
			]
		],
		
		// Группа 4: Двигатели
		'specs_power' => [
			'label' => 'Силовая установка',
			'fields' => [
				'engine' => ['label' => 'Двигатели', 'unit' => '', 'icon' => 'specs/engine'],
				'thrust' => ['label' => 'Тяга (на двигатель)', 'unit' => 'кН', 'icon' => 'specs/thrust', 'better' => 'max'],
				'fuel_consumption' => ['label' => 'Расход топлива', 'unit' => 'л/пкм', 'icon' => 'specs/fuel-consumption', 'is_key' => true, 'better' => 'min'],
				'run-up' => ['label' => 'Длина разбега', 'unit' => 'м', 'icon' => 'specs/run-up', 'better' => 'min'],
			]
		],			
	];
}