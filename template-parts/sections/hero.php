<?php

// Верстка Hero-секции 

	$image = get_sub_field('hero_background');
	$title = get_sub_field('hero_title');
	$text = get_sub_field('hero_description');
?>

<!-- Hero-секция -->
<section class="hero">
	<div class="hero__background">
		
		<?php if ($image):
			$image_url = $image['url'];
			$image_alt = $image['alt'];
		?>
		<img class="hero__image" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_html($image_alt); ?>" loading="eager" fetchpriority="high" />
		<?php endif; ?>
		<div class="hero__overlay"></div>
	</div>

	<div class="container">
		<div class="hero__content">	
			
			<?php if ($title): ?>
				<h1 class="hero__title"><?php echo esc_html($title); ?></h1>
			<?php endif; ?>
			
			<?php if ($text): ?>
				<p class="hero__description"><?php echo esc_html($text); ?></p>
			<?php endif; ?>
		
		</div>
	</div>

	<button type="button" class="hero__scroll-btn" data-target="#brands">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</button>
</section>