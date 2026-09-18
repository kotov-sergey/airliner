<?php
// Сравнительная таблица авиалайнеров

$compare_query = $args['query'] ?? null;
if ( ! $compare_query || ! $compare_query->have_posts() ) return;

// Массив данных авиалайнеров
$planes = [];

while ( $compare_query->have_posts() ) {
    $compare_query->the_post();

    $plane_id = get_the_ID();
    
    $brands = wp_get_post_terms( $plane_id, 'manufacturer' );
    $body_types = wp_get_post_terms( $plane_id, 'body-type' );

    $planes[] = [
        'id' => $plane_id,
        'title' => get_the_title(),
        'permalink' => get_permalink(),
        'thumbnail' => get_the_post_thumbnail_url( $plane_id, 'large' ) ?: get_template_directory_uri() . '/public/images/placeholder-image.svg',
        'brand' => ! empty( $brands ) ? $brands[0]->name : '—',
        'body_type' => ! empty( $body_types ) ? $body_types[0]->name : '—',
        'passengers' => get_field( 'passengers', $plane_id ) ?: '—',
        'range' => get_field( 'range', $plane_id ) ?: '—',
        'speed' => get_field( 'speed', $plane_id ) ?: '—',
        'mtow' => get_field( 'mtow', $plane_id ) ?: '—',
    ];
}
wp_reset_postdata();
?>

<!-- Сравнительная таблица -->
<div class="compare-table-wrapper">
    <table class="compare-table">
        <tbody>

            <!-- Шапка таблицы -->
            <tr class="compare-table__row compare-table__row--header">
                <th class="compare-table__label">Лайнер</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell compare-table__cell--plane">
                        
                        <!-- Кнопка удаления из сравнения -->
                        <button type="button"
                            class="compare-table__remove js-compare-btn"
                            data-id="<?php echo esc_attr( $plane['id']); ?>"
                            title="Удалить из сравнения"
                            aria-label="Удалить <?php echo esc_attr( $plane['title']); ?> из сравнения">
                            &times;
                        </button>

                        <!-- Изображение авиалайнера -->
                        <div class="compare-table__preview">
                            <img src="<?php echo esc_url( $plane['thumbnail']); ?>"
                                alt="<?php echo esc_attr( $plane['title']); ?>"
                                class="compare-table__image"
                                loading="lazy" />
                        </div>

                        <!-- Заголовок авиалайнера -->
                        <h3 class="compare-table__plane-title">
                            <a href="<?php echo esc_url( $plane['permalink']); ?>">
                                <?php echo esc_html( $plane['title']); ?>
                            </a>
                        </h3>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Производитель -->
            <tr class="compare-table__row">
                <th class="compare-table__label">Производитель</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell">
                        <span class="badge badge--brand"><?php echo esc_html( $plane['brand'] ); ?></span>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Тип фюзеляжа -->
            <tr class="compare-table__row">
                <th class="compare-table__label">Тип фюзеляжа</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell">
                        <span class="badge badge--body"><?php echo esc_html( $plane['body_type'] ); ?></span>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Вместимость -->
            <tr class="compare-table__row">
                <th class="compare-table__label">Вместимость</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell compare-table__cell--val">
                        <?php echo esc_html( $plane['passengers'] ); ?>
                    </td>
                <?php endforeach; ?>
            </tr>
            
            <!-- Дальность -->
            <tr class="compare-table__row">
                <th class="compare-table__label">Дальность</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell compare-table__cell--val">
                        <?php echo esc_html( $plane['range'] ); ?>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Скорость -->
            <tr class="compare-table__row">
                <th class="compare-table__label">Скорость</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell compare-table__cell--val">
                        <?php echo esc_html( $plane['speed'] ); ?>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Взлетная масса -->
            <tr class="compare-table__row">
                <th class="compare-table__label">Взлетная масса</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell compare-table__cell--val">
                        <?php echo esc_html( $plane['mtow'] ); ?>
                    </td>
                <?php endforeach; ?>
            </tr>

            <!-- Кнопка карточки -->
            <tr class="compare-table__row compare-table__row--action">
                <th class="compare-table__label">Ссылка на модель</th>

                <?php foreach ( $planes as $plane ) : ?>
                    <td class="compare-table__cell">
                        <a href="<?php echo esc_url( $plane['permalink']); ?>" class="btn btn--secondary btn--sm">
                            Страница модели
                        </a>
                    </td>
                <?php endforeach; ?>
            </tr>

        </tbody>
    </table>
</div>