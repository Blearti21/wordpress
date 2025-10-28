<?php
/* Template Name: Products Page */

get_header();
?>

<h1>Produktet tona</h1>

<div class="products-grid">
    <?php
    global $products;

    if (empty($products)) {
        echo '<p>Nuk ka produkte.</p>';
    } else {
        foreach ($products as $p) {
            echo '<div class="product-item">';
            echo '<img src="'.esc_url($p['image']).'" alt="'.esc_attr($p['title']).'" style="width:150px; height:auto;">';
            echo '<h3>'.esc_html($p['title']).'</h3>';
            echo '<p>Çmimi: $'.esc_html($p['price']).'</p>';
            echo '<a href="?add_to_cart='.intval($p['id']).'" class="btn">Shto në Shportë</a>';
            echo '</div>';
        }
    }
    ?>
</div>

<?php get_footer(); ?>
