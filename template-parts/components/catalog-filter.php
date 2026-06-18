<?php

$is_taxonomy = is_tax();
$current_term = $is_taxonomy ? get_queried_object() : null;
$current_tax = $current_term ? $current_term->taxonomy : '';

$columns = $args['columns'] ?? 3;
?>

<!-- Форма фильтра -->
<form id="airliner-filter" class="catalog-filter">

    <?php if ( $is_taxonomy ) : ?>
        <input type="hidden" name="base_taxonomy" value="<?php echo esc_attr( $current_tax ); ?>">
        <input type="hidden" name="base_term_id" value="<?php echo esc_attr( $current_term->term_id ); ?>">
    <?php endif; ?>

    <input type="hidden" name="grid_columns" value="<?php echo (int) $columns; ?>">

    <?php if ( $current_tax !== 'manufacturer' ) : ?>
        <div class="catalog-filter__block is-open">
            <h3 class="catalog-filter__title">Производитель</h3>
            
            <div class="catalog-filter__content">
                <div class="catalog-filter__inner">
                    <?php
                    $brands = get_terms( ['taxonomy' => 'manufacturer'] );
                    foreach ( $brands as $brand ) : ?>
                        <label class="form-checkbox">
                            <input type="checkbox" name="brand[]" value="<?php echo $brand->term_id; ?>" class="form-checkbox__input" >
                            <span class="form-checkbox__label"><?php echo $brand->name; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( $current_tax !== 'body-type' ) : ?>
        <div class="catalog-filter__block">
            <h3 class="catalog-filter__title">Тип фюзеляжа</h3>
            
            <div class="catalog-filter__content">
                <div class="catalog-filter__inner">
                    <?php
                    $types = get_terms( ['taxonomy' => 'body-type'] );
                    foreach ( $types as $type ) : ?>
                        <label class="form-checkbox">
                            <input type="checkbox" name="fuselage[]" value="<?php echo $type->term_id; ?>" class="form-checkbox__input" >
                            <span class="form-checkbox__label"><?php echo $type->name; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="catalog-filter__block">
        <h3 class="catalog-filter__title">Статус</h3>
        
        <div class="catalog-filter__content">
            <div class="catalog-filter__inner">
                <?php
                $statuses = get_terms( ['taxonomy' => 'airliner-status'] );
                foreach ( $statuses as $status ) : ?>
                    <label class="form-checkbox">
                        <input type="checkbox" name="status[]" value="<?php echo $status->term_id; ?>" class="form-checkbox__input" >
                        <span class="form-checkbox__label"><?php echo $status->name; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>                        

    <div class="catalog-filter__block">
        <h3 class="catalog-filter__title">Дальность полёта</h3>

        <div class="catalog-filter__content">
            <div class="catalog-filter__inner">
                <input type="number" name="range" class="form-input" placeholder="Например: 15 000">
            </div>
        </div>
    </div>

    <div class="catalog-filter__block">
        <h3 class="catalog-filter__title">Вместимость</h3>

        <div class="catalog-filter__content">
            <div class="catalog-filter__inner">
                <input type="number" name="passengers" class="form-input" placeholder="Например: 1 000">
            </div>
        </div>
    </div>                       

    <button type="submit" class="btn btn--primary">Применить</button>
</form>