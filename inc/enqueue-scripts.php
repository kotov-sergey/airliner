<?php

// Определяем константы для путей
define('THEME_DIST_URI', get_template_directory_uri() . '/dist');
define('THEME_DIST_PATH', get_template_directory() . '/dist');
define('VITE_SERVER', 'http://localhost:5173');
define('VITE_ENTRY_POINT', 'src/js/main.js');

function airliner_vite_assets() {
    // Проверяем, запущен ли Vite (для разработки)
    // Самый простой способ: проверить наличие hot-файла или пытаться достучаться до сервера.
    // Для простоты будем использовать переменную окружения или проверку существования манифеста.
    
    // Логика: Если манифеста НЕТ, значит мы скорее всего в DEV режиме (или забыли сделать билд).
    // Либо можно использовать константу в wp-config.php: define('IS_VITE_DEV', true);
    
    $is_dev = false;
    
    // Простая проверка: если мы локально и порт 5173 доступен (это условность)
    // Лучший вариант: проверять, есть ли файл dist/manifest.json. Если нет — считаем, что это dev.
    if ( ! file_exists( THEME_DIST_PATH . '/manifest.json' ) ) {
        $is_dev = true;
    }

    if ( $is_dev ) {
        // --- DEV РЕЖИМ ---
        
        // Подключаем клиент Vite для HMR (Hot Module Replacement)
        wp_enqueue_script('vite-client', VITE_SERVER . '/@vite/client', [], null, true);
        
        // Подключаем наш основной файл (как модуль)
        wp_enqueue_script('vite-main', VITE_SERVER . '/' . VITE_ENTRY_POINT, [], null, true);
        
        // Добавляем атрибут type="module" (обязательно для Vite)
        add_filter('script_loader_tag', 'add_type_attribute', 10, 3);
        
    } else {
        // --- PRODUCTION РЕЖИМ ---
        
        $manifest = json_decode( file_get_contents( THEME_DIST_PATH . '/manifest.json' ), true );
        
        if ( is_array( $manifest ) && isset( $manifest[VITE_ENTRY_POINT] ) ) {
            $js_file = $manifest[VITE_ENTRY_POINT]['file'];
            $css_files = isset($manifest[VITE_ENTRY_POINT]['css']) ? $manifest[VITE_ENTRY_POINT]['css'] : [];

            // Подключаем JS из билда
            wp_enqueue_script('vite-main-prod', THEME_DIST_URI . '/' . $js_file, [], null, true);

            // Подключаем CSS из билда
            foreach ($css_files as $css_file) {
                wp_enqueue_style('vite-style-prod', THEME_DIST_URI . '/' . $css_file, [], null);
            }
        }
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