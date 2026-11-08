<?php

// Настройки компонента
$args = wp_parse_args(
    $args ?? [],
    [
        'columns' => 3,
    ]
);

$columns = max( 1, (int) $args['columns'] );

// Текущая таксономия
$current_term = is_tax() ? get_queried_object() : null;

if ( ! ( $current_term instanceof WP_Term ) ) {
    $current_term = null;
}

// Проверяем принадлежность таксономии к авиалайнерам
$is_airliner_taxonomy = (
    $current_term
    && is_object_in_taxonomy( 'airliner', $current_term->taxonomy )
);

$current_tax = $is_airliner_taxonomy
    ? $current_term->taxonomy
    : '';

?>

<form id="airliner-filter" class="catalog-filters" aria-label="Фильтры авиалайнеров">
    <?php wp_nonce_field( 'filter_airliners', 'filter_airliners_nonce', false ); ?>

    <?php if ( $is_airliner_taxonomy ) : ?>

        <input
            type="hidden"
            name="base_taxonomy"
            value="<?php echo esc_attr( $current_tax ); ?>"
        >

        <input
            type="hidden"
            name="base_term_id"
            value="<?php echo esc_attr( $current_term->term_id ); ?>"
        >

    <?php endif; ?>

    <input
        type="hidden"
        name="grid_columns"
        value="<?php echo esc_attr( $columns ); ?>"
    >

    <!-- Производитель -->
    <?php if ( $current_tax !== 'manufacturer' ) : ?>

        <?php
            $brands = get_terms( [
                'taxonomy'   => 'manufacturer',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ] );
        ?>

        <?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>

            <div class="catalog-filters__block is-open">

                <h3
                    class="catalog-filters__title"
                    id="catalog-filter-manufacturer-title"
                >
                    Производитель
                </h3>

                <div class="catalog-filters__content">

                    <div class="catalog-filters__inner">

                        <?php foreach ( $brands as $brand ) : ?>

                            <label class="form-checkbox">

                                <input
                                    type="checkbox"
                                    name="brand[]"
                                    value="<?php echo esc_attr( $brand->term_id ); ?>"
                                    class="form-checkbox__input"
                                >

                                <span class="form-checkbox__label">
                                    <?php echo esc_html( $brand->name ); ?>
                                </span>

                            </label>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    <?php endif; ?>

    <!-- Тип фюзеляжа -->
    <?php if ( $current_tax !== 'body-type' ) : ?>

        <?php
            $types = get_terms( [
                'taxonomy'   => 'body-type',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ] );
        ?>

        <?php if ( ! empty( $types ) && ! is_wp_error( $types ) ) : ?>

            <div class="catalog-filters__block is-open">

                <h3
                    class="catalog-filters__title"
                    id="catalog-filter-body-type-title"
                >
                    Тип фюзеляжа
                </h3>

                <div class="catalog-filters__content">

                    <div class="catalog-filters__inner">

                        <?php foreach ( $types as $type ) : ?>

                            <label class="form-checkbox">

                                <input
                                    type="checkbox"
                                    name="fuselage[]"
                                    value="<?php echo esc_attr( $type->term_id ); ?>"
                                    class="form-checkbox__input"
                                >

                                <span class="form-checkbox__label">
                                    <?php echo esc_html( $type->name ); ?>
                                </span>

                            </label>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    <?php endif; ?>

    <!-- Статус -->
    <?php if ( $current_tax !== 'airliner-status' ) : ?>

        <?php
            $statuses = get_terms( [
                'taxonomy'   => 'airliner-status',
                'hide_empty' => true,
                'orderby'    => 'name',
                'order'      => 'ASC',
            ] );
        ?>

        <?php if ( ! empty( $statuses ) && ! is_wp_error( $statuses ) ) : ?>

            <div class="catalog-filters__block is-open">

                <h3
                    class="catalog-filters__title"
                    id="catalog-filter-status-title"
                >
                    Статус
                </h3>

                <div class="catalog-filters__content">

                    <div class="catalog-filters__inner">

                        <?php foreach ( $statuses as $status ) : ?>

                            <label class="form-checkbox">

                                <input
                                    type="checkbox"
                                    name="status[]"
                                    value="<?php echo esc_attr( $status->term_id ); ?>"
                                    class="form-checkbox__input"
                                >

                                <span class="form-checkbox__label">
                                    <?php echo esc_html( $status->name ); ?>
                                </span>

                            </label>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    <?php endif; ?>

    <!-- Дальность полёта -->
    <div class="catalog-filters__block is-open">

        <h3
            class="catalog-filters__title"
            id="catalog-filter-range-title"
        >
            Дальность полёта
        </h3>

        <div class="catalog-filters__content">

            <div class="catalog-filters__inner">

                <input
                    type="number"
                    id="catalog-filter-range"
                    name="range"
                    class="form-input"
                    placeholder="Например: 5000"
                    min="0"
                    step="100"
                    aria-labelledby="catalog-filter-range-title"
                >

            </div>

        </div>

    </div>

    <!-- Вместимость -->
    <div class="catalog-filters__block is-open">

        <h3
            class="catalog-filters__title"
            id="catalog-filter-passengers-title"
        >
            Вместимость
        </h3>

        <div class="catalog-filters__content">

            <div class="catalog-filters__inner">

                <input
                    type="number"
                    id="catalog-filter-passengers"
                    name="passengers"
                    class="form-input"
                    placeholder="Например: 150"
                    min="0"
                    step="10"
                    aria-labelledby="catalog-filter-passengers-title"
                >

            </div>

        </div>

    </div>

    <!-- Кнопки фильтров -->
    <div class="catalog-filters__actions">

        <button type="submit" class="btn btn--primary">
            Применить
        </button>

        <button type="reset" class="btn btn--secondary">
            Сбросить
        </button>

    </div>

</form>