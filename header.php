<?php
// Шапка сайта

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

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
	<header class="header">
		<div class="container header__container">

            <!-- Общая обёртка хедера -->
            <div class="header__inner">

                <!-- Верхняя строка -->
                <div class="header__top">
                 
                    <!-- Логотип сайта -->
                    <div class="header__brand">
                        <?php if ( has_custom_logo() ) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url( '/' ) ); ?>" class="header__site-title">
                                <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Поиск по сайту -->
                    <form
                        class="header__search"
                        role="search"
                        aria-label="Поиск по сайту"
                        method="get"
                        action="<?php echo esc_url(home_url( '/' ) ); ?>"
                    >
                        <input
                            type="search"
                            name="s"
                            class="header__search-input"
                            placeholder="Поиск по сайту..."
                            aria-label="Поиск"
                            value="<?php echo esc_attr( get_search_query( false ) ); ?>"
                            autocomplete="off"
                        >

                        <button
                            type="submit"
                            class="header__search-button"
                            aria-label="Найти"
                        >
                        	<svg
								class="header__search-icon"
								width="18"
								height="18"
								viewBox="0 0 24 24"
								fill="none"
								stroke="currentColor"
								stroke-width="2"
								aria-hidden="true"
								focusable="false"
							>
								<circle cx="11" cy="11" r="8"></circle>
								<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							</svg>
                        </button>
                    </form>

                    <!-- Пользовательские действия -->
                    <div class="header__actions">

                        <!-- Избранное -->
                        <a 
                            href="<?php echo esc_url( home_url( '/favorites/' ) ); ?>"
                            class="header__action"
                            aria-label="Избранное"
                            data-favorites-link
                        >
                            <span class="header__action-icon" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								    <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
								</svg>

                                <span class="header__action-count" data-favorites-count aria-hidden="true">0</span>
                            </span>

                            <span class="header__action-label">Избранное</span>
                        </a>

                        <!-- Сравнение -->
                        <a 
                            href="<?php echo esc_url( home_url( '/compare/' ) ); ?>"
                            class="header__action"
                            aria-label="Сравнение"
                            data-compare-link
                        >
                            <span class="header__action-icon" aria-hidden="true">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" aria-hidden="true">
									<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 12H3M16 6H3M16 18H3M18 9v6M21 12h-6"></path>
								</svg>

                                <span class="header__action-count" data-compare-count aria-hidden="true">0</span>
                            </span>

                            <span class="header__action-label">Сравнение</span>
                        </a>

                    </div>

                </div>

                <!-- Нижняя строка-->
                <div class="header__bottom">

                    <!-- Бургер-меню -->
                    <button
                        type="button"
                        class="header__burger" 
                        aria-label="Открыть меню"
                        aria-expanded="false"
                        aria-controls="header-nav"
                    >
                        <span class="header__burger-icon"></span>
                    </button>
                    
                    <!-- Меню навигации сайта -->
                    <nav id="header-nav" class="header__nav" inert aria-label="Основная навигация">
                        <?php 
                            wp_nav_menu( array(
                                'theme_location' => 'primary',
                                'menu_class'     => 'header__menu',
                                'menu_id'        => 'header-menu',
                                'container'		 => false,
                                'fallback_cb'    => false,
                            )); 
                        ?>
                    </nav>
                
                </div>

            </div>

		</div>
	</header>