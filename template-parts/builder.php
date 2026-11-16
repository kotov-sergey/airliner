<?php
// Умный вывод секций в зависимости от страницы

$page_id = $args['page_id'] ?? false; 

// Счетчик для нумерации секций
$section_counter = 0;

// Массив исключенных из нумерации секций
$excluded_layouts = ['hero', 'cta-home', 'cta-simple'];

$is_front_page = is_front_page();

if ( have_rows ( 'content_blocks', $page_id ) ) {
	while ( have_rows ( 'content_blocks', $page_id ) ) {

		the_row();
		$layout = get_row_layout();
		
		// Подсчет порядкового номера секции
		if ( !in_array( $layout, $excluded_layouts, true ) ) {
			$section_counter++;
			$formatted_index = sprintf( "%02d", $section_counter );
		}
		else {
			$formatted_index='';
		}
		
		// Аргументы для шаблона секции
		$template_args = array(
			'index' => $formatted_index,
			'page_id' => $page_id
		);

		// Формирование пути поиска
		$base_path = 'template-parts/sections/';

		$section_paths = $is_front_page ?
			[
				$base_path . 'front-page/' . $layout,
				$base_path . 'shared/' . $layout,
			]
			:
			[
				$base_path . 'shared/' . $layout,
			];

		// Поиск первого существующего шаблона
		$template_path = false;

		foreach ( $section_paths as $path ) {
			if ( locate_template( $path . '.php' ) ) {
				$template_path = $path;
				break;
			}
		}

		// Полный путь секции без расширения
		if ( $template_path ) {
			get_template_part( $template_path, null, $template_args );
		}

		else {

			// Сообщение об ошибке для администратора сайта
			if ( current_user_can( 'edit_posts' ) ) :
				?>
				
				<div class="container section-error">
                    <div class="section-error__inner">

                        <p><strong>⚠️ Ошибка администратора</strong></p>

                        <p>
                            Секция с лейаутом
                            <code><?php echo esc_html( $layout ); ?></code>
                            существует в админке, но файл шаблона не найден.
                        </p>

                        <p>Проверены следующие пути:</p>

                        <ul>
                            <?php foreach ( $section_paths as $path ) : ?>
                                <li>
                                    <code><?php echo esc_html( $path . '.php' ); ?></code>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <p>
                            Пожалуйста, проверьте корректность названия
                            файла шаблона.
                        </p>

                    </div>
                </div>
				
				<?php
			endif;
		}
	}
}