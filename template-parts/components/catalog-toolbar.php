<?php
// Компонент панели поиска и сортировки каталога

// Если счетчик не передан извне, берем общее число бортов
$total_count  = $args['total_count'] ?? wp_count_posts( 'airliner' )->publish; 
$current_sort = $args['current_sort'] ?? 'popular';
$current_view = $args['current_view'] ?? 'grid';
?>

<div class="catalog-toolbar" data-catalog-toolbar>
    <div class="container catalog-toolbar__container">

        <!-- Скрытый инпут для передачи текущего вида (сетка/строка) в форму -->
        <input type="hidden" name="view" value="<?php echo esc_attr( $current_view ); ?>" form="airliner-filter" data-catalog-view-input>

        <!-- Левая часть: Поиск по модели + Счетчик -->
        <div class="catalog-toolbar__left">

            <!-- Поиск по модели -->
            <div class="catalog-toolbar__search">
                <svg class="catalog-toolbar__search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>

                <input 
                    type="search"
                    name="search"
                    class="catalog-toolbar__input"
                    placeholder="Поиск лайнера (например, A350, 787)..."
                    value="<?php echo esc_attr( $_GET['search'] ?? '' ); ?>"
                    autocomplete="off"
                    form="airliner-filter"
                    data-catalog-search
                />
            </div>

            <!-- Счетчик -->
            <div class="catalog-toolbar__count" aria-live="polite">
                Найдено: <span class="catalog-toolbar__count-num" data-catalog-count><?php echo esc_html( $total_count ); ?></span>
            </div>

        </div>

        <!-- Правая часть: Сортировка + Переключатель вида -->
        <div class="catalog-toolbar__right">

            <!-- Кнопка мобильных фильтров -->
            <button type="button" class="catalog-toolbar__filter-btn" data-toggle-filters aria-label="Открыть фильтры">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span>Фильтры</span>
            </button>

            <!-- Селект сортировки по ТТХ -->
            <div class="catalog-toolbar__sort">
                <label for="catalog-sort" class="catalog-toolbar__sort-label">Сортировка:</label>
                <div class="catalog-toolbar__select-wrapper">
                    <select id="catalog-sort" name="sort" class="catalog-toolbar__select" form="airliner-filter" data-catalog-sort>
                        <option value="popular" <?php selected( $current_sort, 'popular' ); ?>>По популярности</option>
                        <option value="range_desc" <?php selected( $current_sort, 'range_desc' ); ?>>По дальности полета</option>
                        <option value="capacity_desc" <?php selected( $current_sort, 'capacity_desc' ); ?>>По вместимости</option>
                        <option value="name_asc" <?php selected( $current_sort, 'name_asc' ); ?>>По названию</option>
                        <option value="year_desc" <?php selected( $current_sort, 'year_desc' ); ?>>Сначала новинки</option>
                    </select>
                </div>
            </div>

            <!-- Переключатель: Сетка / Строка -->
            <div class="catalog-toolbar__views" role="group" aria-label="Вид отображения каталога">
                <button 
                    type="button" 
                    class="catalog-toolbar__view-btn <?php echo ( $current_view === 'grid' ) ? 'is-active' : ''; ?>" 
                    data-catalog-view="grid"
                    aria-label="Отобразить плиткой"
                    title="Вид: Сетка"
                >
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </button>

                <button 
                    type="button" 
                    class="catalog-toolbar__view-btn <?php echo ( $current_view === 'table' ) ? 'is-active' : ''; ?>" 
                    data-catalog-view="table"
                    aria-label="Отобразить строкой"
                    title="Вид: Строка"
                >
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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