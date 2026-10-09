<?php
// Компонент быстрых фильтров (Бренды + Типы фюзеляжа)

$show_brands = $args['show_brands'] ?? true;
$show_types = $args['show_types'] ?? true;
?>
 <div class="catalog-pills">
    <div class="container catalog-pills__container">

        <!-- Навигация по Брендам-->
        <?php if ( $show_brands ) : ?>
            <div class="catalog-pills__row">
                <span class="catalog-pills__label">Бренд</span>

                <?php 
                    get_template_part( 'template-parts/components/terms-nav', null, [
                        'taxonomy' => 'manufacturer',
                        'all_label' => 'Все бренды'
                    ] ); 
                ?>
            </div>
        <?php endif; ?>

        <!-- Навигация по Типам фюзеляжа -->
        <?php if ( $show_types ) : ?>
            <div class="catalog-pills__row">
                <span class="catalog-pills__label">Тип фюзеляжа</span>

                <?php 
                    get_template_part( 'template-parts/components/terms-nav', null, [
                        'taxonomy' => 'body-type',
                        'all_label' => 'Все типы'
                    ] ); 
                ?>
            </div>
        <?php endif; ?>

    </div>
</div>