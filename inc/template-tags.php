<?php

// Функция вывода всех производителей (брендов) списком
function the_airliner_footer_all_brands( $limit = null ) {
    $brands = get_terms([
        'taxonomy' => 'manufacturer',
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
        'number' => $limit,
    ]);

    if ( ! $brands || is_wp_error( $brands ) ) return;

    echo '<ul class="footer__list">';

    foreach ( $brands as $brand ) {
        $name = $brand->name;
        $link = get_term_link( $brand );

        if ( is_wp_error( $link )  ) continue;

        echo '<li class="footer__item">';
            
            echo '<a href="' . esc_url( $link ) . '" class="brand-link">';
                echo esc_html( $name );
            echo '</a>';

        echo '</li>';
    }

    echo '</ul>';
}

// Функция вывода логотипа бренда
function the_airliner_brand_logo( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();

    $terms = get_the_terms( $post_id, 'manufacturer' );

    if ( ! $terms || is_wp_error( $terms ) ) return;

    $brand = $terms[0];

    $logo = get_field( 'brand_logo', $brand );
    
    $name = $brand->name;
    $link = get_term_link( $brand );

    if ( is_wp_error( $link )  ) return;


    if ( $logo ) {
        echo '<a href="'. esc_url( $link ) .'" class="airliner-brand-logo" title="' . esc_attr( $brand->name ) .'">';
            echo wp_get_attachment_image( $logo['id'], 'full', false, ['class' => 'brand-img'] );
        echo '</a>';
    }
    else {
        echo '<a href="' . esc_url( $link ) . '" class="airliner-brand-text">' . esc_html( $name ) . '</a>';
    }
}

// Функция вывода всех терминов авиалайнера ( без бренда )
function the_airliner_all_taxonomies( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();

    $taxonomies = get_object_taxonomies( 'airliner', 'objects' );

    if ( empty( $taxonomies ) ) return;

    echo '<div class="airliner-meta-list">';

    foreach ( $taxonomies as $taxonomy_slug => $taxonomy_obj ) {
        if ( in_array( $taxonomy_slug, ['category', 'post_tag', 'brand'] ) ) continue;
        
        $terms = get_the_terms( $post_id, $taxonomy_slug );

        if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
            $links = [];

            foreach ( $terms as $term ) {
                $link = get_term_link( $term );

                if ( is_wp_error( $link ) ) continue;

                $links[] = '<a href="'. esc_url( $link ) .'" class="meta-value">' . esc_html( $term->name ) . '</a>';
            }

            echo '<div class="meta-item">';
            echo '<span class="meta-label">' . esc_html( $taxonomy_obj->label ) . ': </span>';
            echo implode( ', ', $links );
            echo '</div>';
        }
    }

    echo '</div>';

}

// Функция вывода мета-бейджиков у авиалайнеров
function the_airliner_badges( $taxonomies = [ 'manufacturer', 'body-type', 'airliner-status' ], $container_class = null ) {
    $post_id = get_the_ID();

    $container_class = 'airliner-badges airliner-badges--' . $container_class;

    if ( empty( $taxonomies ) ) return;

    echo '<div class="' . $container_class . '">';

    foreach ( $taxonomies as $tax_slug ) {
        $terms = get_the_terms( $post_id, $tax_slug );

        if ( ! $terms || is_wp_error( $terms ) ) continue;
    

        foreach ( $terms as $term ) {

            $link = get_term_link( $term );
            if ( is_wp_error( $link ) ) continue;

            $name = $term->name;

            $css_class = 'badge badge--' . $tax_slug;

            echo '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $css_class ) . '">';
                echo esc_html( $name );
            echo '</a>';
        }
    }

    echo '</div>';
}

// Вывод одной характеристики из массива данных авиалайнера
function the_airliner_spec( $group_key, $field_key, $css_mod='' ) {
    $config = get_airliner_specs_config();

    if ( ! isset( $config[$group_key]['fields'][$field_key] ) ) return;

    $field_config = $config[$group_key]['fields'][$field_key];

    $group_data = get_field( $group_key );

    if ( empty( $group_data ) || empty( $group_data[$field_key] ) ) return;

    $value = $group_data[$field_key];

    if ( is_numeric( $value ) ) {
        $value = number_format( $value, 0, '.', ' ' );
    }

    $icon = airliner_get_svg( $field_config['icon'] );
    $label = $field_config['label'];
    $unit = $field_config['unit'];

    $classes = 'spec-row';

    if ( ! empty( $css_mod ) ) {
        $mods_array = explode( ' ', $css_mod );
        foreach ( $mods_array as $mod ) {
            $mod = trim( $mod );
            if ( ! empty( $mod ) ) {
                $classes .= ' spec-row--' . $mod;
            }
        }
    }

    echo '<div class="' . esc_attr( $classes ) . '" title="' . esc_attr( $label ) . '">';

        echo '<div class="spec-row__name">';
            echo '<div class="spec-row__icon">' . $icon . '</div>';
            echo '<span class="spec-row__label">' . esc_html( $label ) . '</span>';
        echo '</div>';

        echo '<div class="spec-row__data">';
            echo '<span class="spec-row__value">' . esc_html( $value ) . '</span>';
            echo '<span class="spec-row__unit">' . esc_html( $unit ) . '</span>';
        echo '</div>';
    
    echo '</div>';
}

// Генерация классов сетки для элементов галереи
 function get_gallery_grid_classes( $total_count, $index ) {

    $prefix = 'gallery-card';

    $layouts = [

        1 => [
            0 => ['__lg--col-span-8', '__lg--col-start-3', '__lg--row-span-2'],
        ],

        3 => [
            0 => ['__lg--col-span-8', '__lg--col-start-1', '__lg--row-span-2'],
            1 => ['__lg--col-span-4', '__lg--col-start-9'],
            2 => ['__lg--col-span-4', '__lg--col-start-9'],
        ],      

        5 => [
            0 => ['__lg--col-span-3', '__lg--col-start-1', '__lg--row-start-1'],
            1 => ['__lg--col-span-3', '__lg--col-start-1', '__lg--row-start-2'],
            2 => ['__lg--col-span-6', '__lg--col-start-4', '__lg--row-span-2', '__lg--row-start-1'],
            3 => ['__lg--col-span-3', '__lg--col-start-10', '__lg--row-start-1'],
            4 => ['__lg--col-span-3', '__lg--col-start-10', '__lg--row-start-2'],
        ],  

        7 => [
            0 => ['__lg--col-span-4', '__lg--col-start-1'],
            1 => ['__lg--col-span-4', '__lg--col-start-5'],
            2 => ['__lg--col-span-4', '__lg--col-start-9'],
            3 => ['__lg--col-span-3', '__lg--col-start-1'],
            4 => ['__lg--col-span-3', '__lg--col-start-4'],
            5 => ['__lg--col-span-3', '__lg--col-start-7'],
            6 => ['__lg--col-span-3', '__lg--col-start-10'],
        ],

        9 => [
            0 => ['__lg--col-span-4', '__lg--col-start-1'],
            1 => ['__lg--col-span-4', '__lg--col-start-5'],
            2 => ['__lg--col-span-4', '__lg--col-start-9'],
            3 => ['__lg--col-span-2', '__lg--col-start-1'],
            4 => ['__lg--col-span-2', '__lg--col-start-3'],
            5 => ['__lg--col-span-2', '__lg--col-start-5'],
            6 => ['__lg--col-span-2', '__lg--col-start-7'],
            7 => ['__lg--col-span-2', '__lg--col-start-9'],
            8 => ['__lg--col-span-2', '__lg--col-start-11'],
        ],       
    ];

    $classes = [];

    if ( isset( $layouts[$total_count] ) && isset( $layouts[$total_count][$index] ) ) {

        foreach ( $layouts[$total_count][$index] as $modifier ) {
            $classes[] = $prefix . $modifier;
        }
    }

    else {
        $classes[] = $prefix . '__lg--span-3';
    } 

    return implode( ' ', $classes );
}