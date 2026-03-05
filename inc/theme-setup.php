<?php
/**
 * Настройка темы и регистрация функционала
 */

if ( ! function_exists( 'airliner_setup' ) ) {
	
	function airliner_setup() {
		
		// Поддержка миниатюр (Post Thumbnails)
		add_theme_support( 'post-thumbnails' );

		// Поддержка кастомного логотипа
		add_theme_support( 'custom-logo', array(
			'height'      => 40,
			'width'       => 150,
			'flex-height' => true,
			'flex-width'  => true,
		) );

		// Поддержка HTML5 разметки
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		// Регистрация областей меню
		register_nav_menus( array(
			'primary'   => 'Главное меню',
			'secondary' => 'Служебное меню',
		) );
	}
}

add_action( 'after_setup_theme', 'airliner_setup' );


/**
 * Регистрация сайдбаров (Виджетов)
 */
function airliner_widgets_init() {
	register_sidebar( array(
		'name'          => 'Sidebar',
		'id'            => 'right_sidebar',
		'description'   => 'Боковая колонка',
		'class'         => 'sidebar',
		'before_widget' => '<div id="%1$s" class="sidebar_widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'airliner_widgets_init' );


/**
 * Очистка WordPress от лишнего мусора (Emojis)
 */
function airliner_cleanup_head() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'after_setup_theme', 'airliner_cleanup_head' );


/**
 * Вспомогательные настройки
 */

// Добавляет SVG в список разрешенных для загрузки файлов
function svg_upload_allow( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'svg_upload_allow' );

// Скрытие админ-панели на фронтенде (черная полоса сверху)
show_admin_bar( false );

// Изменение окончания отрывка [...] на троеточие
add_filter( 'excerpt_more', fn() => '...' );