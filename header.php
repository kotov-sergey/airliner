<?php
// Шаблон шапки сайта

// Логика переключения классов шапки
$block_name = 'header';
$header_class = $block_name;

if ( is_front_page() ) {
    $header_class .= " {$block_name}--transparent";
} 
else {
    $header_class .= " {$block_name}--solid";
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>

    <!-- Подключение Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body <?php body_class( 'page' ); ?>>
	<header class="<?php echo esc_attr( $header_class ); ?>">
		<div class="container header__container">

			<!-- Логотип сайта -->
			<?php the_custom_logo(); ?>
			
			<!-- Бургер-меню -->
			<button class="burger__btn" aria-label="Открыть меню">
			  <span class="burger__icon"></span>
			</button>
			
			<!-- Меню навигации сайта -->
			<nav id="navMenu" class="header__nav" aria-label="Меню в шапке">
				<?php wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_class'     => 'header__list',
					'menu_id'        => 'header__menu',
					'container'		 => false
				)); ?>
			</nav>
		</div>
	</header>