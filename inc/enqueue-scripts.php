<?php

// Определяем константы для путей
define('THEME_DIST_URI', get_template_directory_uri() . '/dist');
define('THEME_DIST_PATH', get_template_directory() . '/dist');
define('VITE_SERVER', 'http://localhost:5173');
define('VITE_ENTRY_POINT', 'src/js/main.js');

function airliner_vite_assets() {
    
    $is_dev = false;
    $script_handle = ''; // Создаем переменную для имени скрипта
    
    if ( ! file_exists( THEME_DIST_PATH . '/manifest.json' ) ) {
        $is_dev = true;
    }

    if ( $is_dev ) {
        // --- DEV РЕЖИМ ---
        $script_handle = 'vite-main'; // Запоминаем имя

        wp_enqueue_script('vite-client', VITE_SERVER . '/@vite/client', [], null, true);
        wp_enqueue_script($script_handle, VITE_SERVER . '/' . VITE_ENTRY_POINT, [], null, true);
        
        add_filter('script_loader_tag', 'add_type_attribute', 10, 3);
        
    } else {
        // --- PRODUCTION РЕЖИМ ---
        $manifest = json_decode( file_get_contents( THEME_DIST_PATH . '/manifest.json' ), true );
        
        if ( is_array( $manifest ) && isset( $manifest[VITE_ENTRY_POINT] ) ) {
            
            $script_handle = 'vite-main-prod'; // Запоминаем имя

            $js_file = $manifest[VITE_ENTRY_POINT]['file'];
            $css_files = isset($manifest[VITE_ENTRY_POINT]['css']) ? $manifest[VITE_ENTRY_POINT]['css'] : [];

            wp_enqueue_script($script_handle, THEME_DIST_URI . '/' . $js_file, [], null, true);

            foreach ($css_files as $css_file) {
                wp_enqueue_style('vite-style-prod', THEME_DIST_URI . '/' . $css_file, [], null);
            }
        }
    }

    // --- ПЕРЕДАЧА ДАННЫХ ДЛЯ AJAX ---
    // Проверяем, был ли подключен какой-либо JS файл (DEV или PROD)
    if ( $script_handle ) {
        wp_localize_script( 
            $script_handle, // Подставляем правильное имя динамически!
            'airlinerAjax', // Имя объекта в JavaScript (window.airlinerAjax)
            [
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'catalog_filter_nonce' )
            ]
        );
    }
}

// Хелпер для добавления type="module"
function add_type_attribute($tag, $handle, $src) {
    if ($handle === 'vite-client' || $handle === 'vite-main') {
        return '<script type="module" src="' . esc_url($src) . '"></script>';
    }
    return $tag;
}

add_action('wp_enqueue_scripts', 'airliner_vite_assets');