<?php
// Верстка подвала сайта

?>
<footer class="footer">
	<div class="container">
		
		<div class="l-grid l-grid--4 footer__grid">

			<div class="footer__column">
				
                <!-- Логотип сайта -->
				<div class="footer__logo">
					<?php the_custom_logo(); ?>
				</div>

                <!-- Краткое описание сайта -->
				<p class="footer__description">Ваш гид в мире гражданской авиации. История, технологии и новости авиаиндустрии.</p>

			</div>

			<div class="footer__column">

				<h3 class="footer__title">Меню</h3>
				
                <!-- Второстепенное меню -->
				<nav class="footer__nav" aria-label="Меню в подвале">
					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => 'footer__list',
						'menu_id'        => 'footer__menu',
						'container'		 => false,
						'depth'          => '1'
					)); ?>
				</nav>

			</div>
			
			<div class="footer__column">

				<h3 class="footer__title">Бренды</h3>
				
                <!-- Основные бренды (производители) -->
				<?php the_airliner_footer_all_brands( 8 ); ?>

			</div>
			
			<div class="footer__column">

				<h3 class="footer__title">Полезные ссылки</h3>
				
                <!-- Меню полезных ссылок -->
				<?php wp_nav_menu(array(
					'theme_location' => 'secondary',
					'menu_class'     => 'footer__list',
					'container'		 => false,
					'menu_id'        => ''
				)); ?>

			</div>

		</div>	

		<div class="footer__bottom">

            <!-- Копирайт сайта -->
			<div class="footer__copyright">
				<p class="footer__copy">
					&copy; <?php echo date( 'Y' ); ?> <?php bloginfo('name'); ?>. Все права защищены.
				</p>
			</div>

            <!-- Общие юридические ссылки -->
			<div class="footer__links">

                <!-- Страница Политики конфиденциальности-->
                <?php if ( get_privacy_policy_url() ) : ?>
                    <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Политика конфиденциальности</a>
                <?php endif; ?>

                <!-- Страница Условия использования-->
				<a href="<?php echo esc_url( home_url( '/terms-of-use' ) ); ?>">Условия использования</a>

			</div>

		</div>
		
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>