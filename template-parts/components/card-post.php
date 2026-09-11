<?php
// Карточка статьи

// Модификатор-раскладка для карточки статьи
$layout = $args['layout'] ?? 'standard';

// Alt-аттрибут для изображения
$alt_text = get_the_title();

// Путь к картинке-заглушке
$placeholder = get_template_directory_uri() . '/public/images/placeholder-image.svg';
?>

<!-- Карточка избранной статьи Featured ( слева изображение, справа текст) -->
<?php if ( $layout === 'featured' ) : ?>

	<article class="featured-card">

		<!-- Сетка карточки -->
		<div class="featured-card__grid">

			<!-- Основное изображение -->
			<div class="featured-card__media">
				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'large', [ 'class' => 'featured-card__image', 'alt' => $alt_text ] ); ?>
				<?php else : ?>
					<img src="<?php echo esc_url( $placeholder ); ?>" class="featured-card__image" alt="Изображение самолёта" />
				<?php endif; ?>
			</div>

			<!-- Контент статьи -->
			<div class="featured-card__content">

				<!-- Мета-данные статьи -->
				<div class="featured-card__meta">
					<?php get_template_part( 'template-parts/components/post-meta' ); ?>
				</div>

				<!-- Заголовок статьи -->
				<h3 class="featured-card__title">
					<a href="<?php the_permalink(); ?>" class="featured-card__link" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a>
				</h3>

				<!-- Описание статьи -->
				<div class="featured-card__description">
					<?php echo wp_trim_words( get_the_excerpt(), 35, '...' ); ?>
				</div>

				<!-- Кнопка статьи -->
				<span class="btn btn--primary featured-card__button">Читать статью</span>

			</div>
		
		</div>

	</article>

<!-- Карточка статьи для Bento-сетки ( с затемнением ) -->
<?php elseif ( $layout === 'overlay' ) : ?>

	<article class="promo-card">

		<!-- Фоновое изображение статьи -->
		<div class="promo-card__background">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', [ 'class' => 'promo-card__image', 'alt' => $alt_text ] ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( $placeholder ); ?>" class="promo-card__image" alt="Изображение самолёта" />
			<?php endif; ?>
		</div>

		<!-- Контент статьи -->
		<div class="promo-card__content">

			<!-- Мета-данные статьи -->
			<div class="promo-card__meta">
				<?php get_template_part( 'template-parts/components/post-meta', null, [
					'modifier' => 'post-meta--inverse'
				] ); ?>
			</div>

			<!-- Заголовок статьи -->
			<h3 class="promo-card__title">
				<a href="<?php the_permalink(); ?>" class="promo-card__link" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			
			<!-- Описание статьи -->
			<div class="promo-card__description">
				<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
			</div>

			<!-- Кнопка статьи -->
			<div class="promo-card__action">
				<span class="promo-card__button">Читать</span>

				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M5 12h14M12 5l7 7-7 7"/>
				</svg>
			</div>

		</div>
		
	</article>

<?php else : ?>

	<!-- Простая карточка статьи -->
	<article class="post-card">

		<!-- Главное изображение статьи -->
		<div class="post-card__media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', [ 'class' => 'post-card__image', 'alt' => $alt_text ] ); ?>
			<?php else : ?>
				<img src="<?php echo esc_url( $placeholder );?>" class="post-card__image" alt="Изображение самолёта" />
			<?php endif; ?>
		</div>

		<!-- Контент статьи -->
		<div class="post-card__content">

			<!-- Мета-данные статьи -->
			<div class="post-card__meta">
				<?php get_template_part( 'template-parts/components/post-meta' ); ?>
			</div>

			<!-- Заголовок статьи -->
			<h3 class="post-card__title">
				<a href="<?php the_permalink(); ?>" class="post-card__link" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
				</a>
			</h3>
			
			<!-- Описание статьи -->
			<div class="post-card__description">
				<?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
			</div>
		</div>

	</article>

<?php endif; ?>