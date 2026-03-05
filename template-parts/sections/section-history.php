<?php

// Верстка секции история авиалайнеров

$section_header = airliner_prepare_header_args( get_sub_field( 'section_header' ), $args );
$timeline = get_sub_field( 'timeline' );
$section_bg = get_sub_field( 'section_image' );
?>

<!-- Секция эволюция гражданской авиации -->
<section class="section section-history section--white">
    
	<?php if ( $section_bg ) : ?>
		<div class="section-history__bg">

        	<?php 
            echo wp_get_attachment_image( $section_bg['id'], 'full', false, array(
                'class' => 'section-history__bg-image',
                'alt'   => '',
                'aria-hidden' => 'true'
            ) ); 
            ?>
			
		</div>
	<?php endif; ?>

		<div class="container">
			
			<?php get_template_part( 'template-parts/components/section-header', null, $section_header ); ?>
			
			<?php if ( $timeline ): ?>

				<div class="timeline">
				
					<?php 

						$i = 0;

						foreach( $timeline as $timeline_item ) :
							$i++;
							$row_class = $i % 2 == 0 ? 'timeline__row--even' : 'timeline__row--odd';
					?>
					
						<div class="timeline__row <?php echo $row_class; ?>">

							<div class="timeline__content">

							<?php if ( $timeline_item['year'] ) : ?>
								<div class="timeline__date">
									<?php echo esc_html( $timeline_item['year'] ); ?>
								</div>
							<?php endif; ?>

							<?php if ( $timeline_item['title'] ) : ?>
								<h3 class="timeline__title">
									<?php echo esc_html( $timeline_item['title'] ); ?>
								</h3>
							<?php endif; ?>

							<?php if ( $timeline_item['description'] ) : ?>
								<p class="timeline__description">
									<?php echo esc_html( $timeline_item['description'] ); ?>
								</p>
							<?php endif; ?>

							</div>

							<div class="timeline__dot"></div>

							<div class="timeline__spacer"></div>

						</div>
						
					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</div>
</section>