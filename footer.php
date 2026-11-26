<?php
// Подвал сайта

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
				
                <!-- Основное меню -->
				<nav class="footer__nav" aria-label="Меню в подвале">
					<?php 
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'menu_class'     => 'footer__list',
							'menu_id'        => 'footer__menu',
							'container'		 => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						) ); 
					?>
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
				<?php 
					wp_nav_menu( array(
						'theme_location' => 'secondary',
						'menu_class'     => 'footer__list',
						'container'		 => false,
						'fallback_cb'    => false,
					) ); 
				?>

			</div>

		</div>	

		<div class="footer__bottom">

            <!-- Копирайт сайта -->
			<div class="footer__copyright">
				<p class="footer__copy">
					&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?>
					<?php echo esc_html( get_bloginfo( 'name' ) ); ?>.
					Все права защищены.
				</p>
			</div>

            <!-- Общие юридические ссылки -->
			<div class="footer__links">

                <!-- Страница Политики конфиденциальности-->
                <?php 
					$privacy_url = get_privacy_policy_url();

					if ( $privacy_url ) : 
				?>
                    	<a href="<?php echo esc_url( $privacy_url ); ?>">
							Политика конфиденциальности
						</a>
                <?php endif; ?>

                <!-- Страница Условия использования-->
				<?php
					$terms_page = get_page_by_path( 'terms-of-use' );

					if ( $terms_page && $terms_page->post_status === 'publish' ) :
				?>
					<a href="<?php echo esc_url( get_permalink( $terms_page ) ); ?>">
						Условия использования
					</a>
				<?php endif; ?>

			</div>

		</div>
		
	</div>
</footer>

<!-- Плашка уведомления избранных авиалайнеров и сравнения -->
<?php get_template_part( 'template-parts/components/ui/notification-toast' ); ?>

<!-- Основной Хук Wordpress-->
<?php wp_footer(); ?>

</body>
</html>