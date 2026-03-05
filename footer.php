<footer class="footer">
	<div class="container">
		
		<div class="footer__grid">
			<div class="footer__column">
				
				<div class="footer__logo">
					<?php the_custom_logo(); ?>
				</div>

				<p class="footer__description">Ваш гид в мире гражданской авиации. История, технологии и новости авиаиндустрии.</p>
			</div>

			<div class="footer__column">
				<h3 class="footer__title">Меню</h3>
				
				<nav id="navMenu" class="footer__nav" aria-label="Меню в подвале">
					<?php wp_nav_menu(array(
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
				
				<?php the_airliner_footer_all_brands( 8 ); ?>
				
			</div>
			
			<div class="footer__column">
				<h3 class="footer__title">Полезные ссылки</h3>
				
				<?php wp_nav_menu(array(
					'theme_location' => 'secondary',
					'menu_class'     => 'footer__list',
					'container'		 => false,
					'menu_id'        => ''
				)); ?>
			</div>
		</div>	

		<div class="footer__bottom">
			<div class="footer__copyright">
				<p class="footer__copy">
					&copy; <?php echo date( 'Y' ); ?> <?php bloginfo('name'); ?>. Все права защищены.
				</p>
			</div>

			<div class="footer__links">
				<a href="#">Политика конфиденциальности</a>
				<a href="#">Пользовательское соглашение</a>
				<a href="#">Условия использования</a>
			</div>
		</div>
		
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>