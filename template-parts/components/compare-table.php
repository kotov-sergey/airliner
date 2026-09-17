<?php
// Сравнительная таблица авиалайнеров

$compare_query = $args['query'] ?? null;
if ( ! $compare_query || ! $compare_query->found_posts ) return;

$planes = [];

$planes[] = [
    'plane_id' => get_the_id(),
]
 ?>

<!-- Сравнительная таблица -->
<section class="section compare-table-wrapper">
    <table class="compare-table">
        <!-- Данные авиалайнеров -->
        <p>Test</p>
    </table>
</section>