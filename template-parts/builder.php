<?php
/*
*	Цикл вывода секций на все страницы сайта
*/

$page_id = $args['page_id'] ?? false; 

$section_counter = 0;
$excluded_layouts = ['hero', 'cta-home', 'cta-simple'];

if ( have_rows ( 'content_blocks', $page_id ) ) {
	while ( have_rows ( 'content_blocks', $page_id ) ) {

		the_row();
		$layout = get_row_layout();

		if ( !in_array( $layout, $excluded_layouts ) ) {
			$section_counter++;
			$formatted_index = sprintf( "%02d", $section_counter );
		}
		else {
			$formatted_index='';
		}
		
		$template_args = array(
			'index' => $formatted_index,
			'page_id' => $page_id
		);

		$path = 'template-parts/sections/' . $layout;

		$file_exists = locate_template( $path . '.php' );

		if ( $file_exists ) {
			get_template_part( $path, null, $template_args );
		}
		else {
			if ( current_user_can( 'edit_posts' ) ) {
				?>
				
				<div class="container" style="margin: 0 auto; margin-bottom: 100px; max-width: 800px;">
					<div style="border: 1px solid red; padding: 20px; margin-top: 20px;">
				
					<p style="text-align: center;"><strong>⚠️ Ошибка администратора</strong></p>
					<p>Секция с лейаутом <code style="color: blue;"><?php echo esc_html( $layout ); ?></code> существует в админке, но файл шаблона не найден.</p>
					<p>Ожидаемый файл: <code style="color: blue;"><?php echo esc_html( $path . '.php' ); ?></code></p>
					<p>Пожалуйста, проверьте, корректно ли введено название файла шаблона.</p>

					</div>
				</div>
				
				<?php 
			}
		}
	}
}