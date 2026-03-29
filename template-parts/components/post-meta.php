<?php 
// Компонент мета-данных карточки

$categories = get_the_category();
$category_name = ! empty( $categories ) ? $categories[0]->name : '';
$date = get_the_date('j M, Y');
$datetime = get_the_date('c');
$read_time = airliner_get_reading_time();

?>

<div class="post-meta">

    <?php if ( $category_name ) : ?>
        <span class="card-post__category">
            <?php echo esc_html( $category_name ); ?>
        </span>
    <?php endif; ?>

    <time class="card-post__date" datetime="<?php echo esc_attr( $datetime ); ?>">
        <?php echo esc_html( $date ); ?>
    </time>
    
    <?php if ( $read_time ) : ?>
        <span class="card-post__read-time">
            <?php echo esc_html( $read_time ); ?>
        </span>
    <?php endif; ?>

</div>