<?php
/**
 * Marketplace Theme - functions.php (me faqe cart)
 */

function marketplace_enqueue_assets() {
    wp_enqueue_style('marketplace-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
    wp_enqueue_style('marketplace-style', get_stylesheet_uri(), array('marketplace-fonts', 'font-awesome'));
}
add_action('wp_enqueue_scripts', 'marketplace_enqueue_assets');

if (session_status() === PHP_SESSION_NONE) session_start();

// Përfshi produktet
require_once get_template_directory() . '/inc/db.php';

// Aktivizo funksione teme
function marketplace_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'marketplace_setup');

// Funksione produktesh
function get_marketplace_products() {
    global $products;
    return $products ?? [];
}

function marketplace_add_to_cart($product_id) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$product_id])) $_SESSION['cart'][$product_id] = 1;
    else $_SESSION['cart'][$product_id]++;
}

function marketplace_remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) unset($_SESSION['cart'][$product_id]);
}

function marketplace_get_cart_items() {
    return $_SESSION['cart'] ?? [];
}

function marketplace_get_cart_total() {
    global $products;
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $qty) {
            if (isset($products[$id])) $total += $products[$id]['price'] * $qty;
        }
    }
    return $total;
}

// Shortcode për produkte
function marketplace_products_shortcode() {
    $products = get_marketplace_products();
    if (empty($products)) return '<p>Nuk ka produkte në dispozicion.</p>';
    $html = '<div class="products-grid">';
    foreach ($products as $p) {
        $html .= '
        <div class="product-item">
            <img src="' . esc_url($p["image"]) . '" alt="' . esc_attr($p["title"]) . '">
            <h3>' . esc_html($p["title"]) . '</h3>
            <p class="price">$' . esc_html($p["price"]) . '</p>
            <a href="?add_to_cart=' . intval($p["id"]) . '" class="btn">Shto në Shportë</a>
        </div>';
    }
    $html .= '</div>';
    return $html;
}
add_shortcode('show_products', 'marketplace_products_shortcode');

// Shortcode për numrin e produkteve
function marketplace_cart_count_shortcode() {
    $count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
    return '<span class="cart-count"><i class="fa-solid fa-cart-shopping"></i> ' . $count . '</span>';
}
add_shortcode('cart_count', 'marketplace_cart_count_shortcode');

// Ridrejtimet për shtim / heqje nga shporta
function marketplace_handle_cart_actions() {
    if (isset($_GET['add_to_cart'])) {
        $id = (int) $_GET['add_to_cart'];
        marketplace_add_to_cart($id);
        $cart_page = get_page_by_path('cart');
        if ($cart_page) wp_redirect(get_permalink($cart_page->ID));
        exit;
    }

    if (isset($_GET['remove_from_cart'])) {
        $id = (int) $_GET['remove_from_cart'];
        marketplace_remove_from_cart($id);
        $cart_page = get_page_by_path('cart');
        if ($cart_page) wp_redirect(get_permalink($cart_page->ID));
        exit;
    }
}
add_action('init', 'marketplace_handle_cart_actions');

// Shortcode për faqen e shportës
function marketplace_cart_page_shortcode() {
    global $products;
    $items = marketplace_get_cart_items();
    if (empty($items)) return '<p>Shporta është bosh.</p>';

    $html = '<h2>Shporta juaj</h2><table class="cart-table">';
    $html .= '<tr><th>Produkt</th><th>Sasia</th><th>Çmimi</th><th>Totali</th><th></th></tr>';

    $total = 0;
    foreach ($items as $id => $qty) {
        if (isset($products[$id])) {
            $p = $products[$id];
            $subtotal = $p['price'] * $qty;
            $total += $subtotal;
            $html .= '<tr>
                <td><img src="' . $p['image'] . '" style="width:60px;"> ' . $p['title'] . '</td>
                <td>' . $qty . '</td>
                <td>$' . $p['price'] . '</td>
                <td>$' . $subtotal . '</td>
                <td><a href="?remove_from_cart=' . $id . '" class="remove-btn">✖</a></td>
            </tr>';
        }
    }

    $html .= '<tr><td colspan="3" style="text-align:right;"><strong>Totali:</strong></td>
              <td><strong>$' . $total . '</strong></td><td></td></tr></table>';
    $html .= '<a href="' . site_url('/') . '" class="btn">↩ Vazhdo Blerjen</a>';
    return $html;
}
add_shortcode('cart_page', 'marketplace_cart_page_shortcode');
?>
