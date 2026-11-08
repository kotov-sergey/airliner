<?php

// Компонент панели поиска и сортировки каталога

// Настройки компонента
$args = wp_parse_args(
    $args ?? [],
    [
        'total_count'  => null,
        'current_sort' => 'default',
        'current_view' => 'grid',
    ]
);

// Общее количество записей
$published_counts = wp_count_posts( 'airliner' );

$default_count = isset( $published_counts->publish )
    ? (int) $published_counts->publish
    : 0;

$total_count = null !== $args['total_count']
    ? absint( $args['total_count'] )
    : $default_count;

// Допустимые варианты сортировки
$allowed_sorts = [
    'default',
    'range_desc',
    'capacity_desc',
    'name_asc',
    'year_desc',
];

$current_sort = sanitize_key( $args['current_sort'] );

if ( ! in_array( $current_sort, $allowed_sorts, true ) ) {
    $current_sort = 'default';
}

// Допустимые варианты отображения
$allowed_views = [
    'grid',
    'table',
];

$current_view = sanitize_key( $args['current_view'] );

if ( ! in_array( $current_view, $allowed_views, true ) ) {
    $current_view = 'grid';
}

// Поисковый запрос
$search_value = '';

if ( isset( $_GET['search'] ) && is_string( $_GET['search'] ) ) {
    $search_value = sanitize_text_field(
        wp_unslash( $_GET['search'] )
    );
}

?>

<div class="catalog-toolbar" data-catalog-toolbar>

    <div class="catalog-toolbar__wrapper">

        <!-- Скрытый инпут для передачи текущего вида -->
        <input
            type="hidden"
            name="view"
            value="<?php echo esc_attr( $current_view ); ?>"
            form="airliner-filter"
            data-catalog-view-input
        >

        <!-- Левая часть: Поиск по модели + Счётчик -->
        <div class="catalog-toolbar__left">

            <!-- Поиск по модели -->
            <div class="catalog-toolbar__search">

                <svg
                    class="catalog-toolbar__search-icon"
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

                <input
                    type="search"
                    name="search"
                    class="catalog-toolbar__input"
                    placeholder="Поиск лайнера (например, A350, 787)..."
                    aria-label="Поиск авиалайнера по названию или модели"
                    value="<?php echo esc_attr( $search_value ); ?>"
                    autocomplete="off"
                    form="airliner-filter"
                    data-catalog-search
                >

            </div>

            <!-- Счётчик -->
            <div class="catalog-toolbar__count" aria-live="polite" aria-atomic="true">

                Найдено:

                <span class="catalog-toolbar__count-num" data-catalog-count>
                    <?php echo esc_html( number_format_i18n( $total_count ) ); ?>
                </span>

            </div>

        </div>

        <!-- Правая часть: Сортировка + Переключатель вида -->
        <div class="catalog-toolbar__right">

            <!-- Кнопка мобильных фильтров -->
            <button
                type="button"
                class="catalog-toolbar__filter-btn"
                data-toggle-filters
                aria-label="Открыть фильтры"
                aria-controls="airliner-filter"
                aria-expanded="false"
            >

                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    aria-hidden="true"
                    focusable="false"
                >
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>

                <span>Фильтры</span>

            </button>

            <!-- Сортировка -->
            <div class="catalog-toolbar__sort">

                <label for="catalog-sort" class="catalog-toolbar__sort-label">
                    Сортировка:
                </label>

                <div class="catalog-toolbar__select-wrapper">

                    <select
                        id="catalog-sort"
                        name="sort"
                        class="catalog-toolbar__select"
                        form="airliner-filter"
                        data-catalog-sort
                    >

                        <option value="default" <?php selected( $current_sort, 'default' ); ?>>
                            По умолчанию
                        </option>

                        <option value="range_desc" <?php selected( $current_sort, 'range_desc' ); ?>>
                            По дальности полёта
                        </option>

                        <option value="capacity_desc" <?php selected( $current_sort, 'capacity_desc' ); ?>>
                            По вместимости
                        </option>

                        <option value="name_asc" <?php selected( $current_sort, 'name_asc' ); ?>>
                            По названию
                        </option>

                        <option value="year_desc" <?php selected( $current_sort, 'year_desc' ); ?>>
                            По новизне
                        </option>

                    </select>

                </div>

            </div>

            <!-- Переключатель: Сетка / Строка -->
            <div
                class="catalog-toolbar__views"
                role="group"
                aria-label="Вид отображения каталога"
            >

                <button
                    type="button"
                    class="catalog-toolbar__view-btn <?php echo $current_view === 'grid' ? 'is-active' : ''; ?>"
                    data-catalog-view="grid"
                    aria-label="Отобразить плиткой"
                    aria-pressed="<?php echo $current_view === 'grid' ? 'true' : 'false'; ?>"
                    title="Вид: Сетка"
                >

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                        focusable="false"
                    >
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>

                </button>

                <button
                    type="button"
                    class="catalog-toolbar__view-btn <?php echo $current_view === 'table' ? 'is-active' : ''; ?>"
                    data-catalog-view="table"
                    aria-label="Отобразить строкой"
                    aria-pressed="<?php echo $current_view === 'table' ? 'true' : 'false'; ?>"
                    title="Вид: Строка"
                >

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        aria-hidden="true"
                        focusable="false"
                    >
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>

                </button>

            </div>

        </div>

    </div>
</div>