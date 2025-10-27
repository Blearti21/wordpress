<?php
/**
 * ================================================================
 * Marketplace Theme - functions.php (Final Version me fotot)
 * ================================================================
 */

/* ---------------------------------------------------------------
   1️⃣ ENQUEUE SCRIPTS & STYLES
---------------------------------------------------------------- */
function marketplace_enqueue_assets() {

    // Google Fonts & Font Awesome
    wp_enqueue_style('marketplace-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap', false, null);
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

    // CSS kryesor
    wp_enqueue_style('marketplace-style', get_stylesheet_uri(), array('marketplace-fonts', 'font-awesome'));

    // JavaScript kryesor
    $main_js_path = get_template_directory() . '/assets/js/main.js';
    $main_js_uri  = get_template_directory_uri() . '/assets/js/main.js';
    $main_js_ver  = file_exists($main_js_path) ? filemtime($main_js_path) : '1.0';

    wp_enqueue_script(
        'marketplace-main-js',
        $main_js_uri,
        array('jquery'),
        $main_js_ver,
        true
    );
}
add_action('wp_enqueue_scripts', 'marketplace_enqueue_assets');


/* ---------------------------------------------------------------
   2️⃣ START PHP SESSION
---------------------------------------------------------------- */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* ---------------------------------------------------------------
   3️⃣ INCLUDE PRODUCT DATA (inc/db.php)
---------------------------------------------------------------- */
$inc_path = get_template_directory() . '/inc/db.php';
if (file_exists($inc_path)) {
    require_once $inc_path;
} else {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>Kujdes:</strong> Skedari <code>inc/db.php</code> mungon!</p></div>';
    });
}


/* ---------------------------------------------------------------
   4️⃣ THEME SUPPORT & MENUS
---------------------------------------------------------------- */
function marketplace_setup_theme() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');

    register_nav_menus(array(
        'primary_menu' => __('Kryemenu', 'marketplace'),
        'footer_menu'  => __('Menyja e poshtme', 'marketplace'),
    ));
}
add_action('after_setup_theme', 'marketplace_setup_theme');

/* ---------------------------------------------------------------
   5️⃣ CUSTOM LOGO SUPPORT
---------------------------------------------------------------- */
add_theme_support('custom-logo', array(
    'height'      => 60,
    'width'       => 200,
    'flex-width'  => true,
    'flex-height' => true,
));


/* ---------------------------------------------------------------
   6️⃣ CUSTOM LOGIN PAGE STYLES
---------------------------------------------------------------- */
function marketplace_login_styles() {
    echo '<style>
        body.login { background: #f5f5f5; font-family: Roboto, sans-serif; }
        .login h1 a { 
            background-image: url(' . get_template_directory_uri() . '/assets/images/logo.png); 
            background-size: contain; width: 220px; height: 80px; 
        }
        .button-primary { background: #0073aa !important; border-color: #005c87 !important; }
        .login form { border-radius: 8px; box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
    </style>';
}
add_action('login_enqueue_scripts', 'marketplace_login_styles');


/* ---------------------------------------------------------------
   7️⃣ PRODUCT FUNCTIONS
---------------------------------------------------------------- */
function get_marketplace_products() {
    global $products;
    return isset($products) ? $products : [];
}

function get_product_by_id($id) {
    global $products;
    return $products[$id] ?? null;
}


/* ---------------------------------------------------------------
   8️⃣ CART FUNCTIONS
---------------------------------------------------------------- */
function marketplace_add_to_cart($product_id) {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id]++;
    }
}

function marketplace_remove_from_cart($product_id) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

function marketplace_clear_cart() {
    unset($_SESSION['cart']);
}

function marketplace_get_cart_items() {
    return $_SESSION['cart'] ?? [];
}

function marketplace_get_cart_total() {
    global $products;
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $qty) {
            if (isset($products[$id])) {
                $total += $products[$id]['price'] * $qty;
            }
        }
    }
    return $total;
}


/* ---------------------------------------------------------------
   9️⃣ SHORTCODES
---------------------------------------------------------------- */
function marketplace_cart_count_shortcode() {
    $count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
    return '<span class="cart-count"><i class="fa-solid fa-cart-shopping"></i> ' . $count . '</span>';
}
add_shortcode('cart_count', 'marketplace_cart_count_shortcode');

function marketplace_products_shortcode() {
    $products = get_marketplace_products();
    if (empty($products)) return '<p>Nuk ka produkte në dispozicion.</p>';

    $html = '<div class="products-grid">';
    foreach ($products as $p) {
        $html .= '
        <div class="product-item">
            <img src="' . esc_url($p['image']) . '" alt="' . esc_attr($p['title']) . '">
            <h3>' . esc_html($p['title']) . '</h3>
            <p class="price">$' . esc_html($p['price']) . '</p>
            <a href="?add_to_cart=' . intval($p['id']) . '" class="btn">Shto në Shportë</a>
        </div>';
    }
    $html .= '</div>';
    return $html;
}
add_shortcode('show_products', 'marketplace_products_shortcode');


/* ---------------------------------------------------------------
   🔟 HANDLE ADD/REMOVE CART ACTIONS (Redirect në Shportë)
---------------------------------------------------------------- */
function marketplace_handle_cart_actions() {
    // Shto produkt
    if (isset($_GET['add_to_cart'])) {
        $product_id = (int) $_GET['add_to_cart'];
        marketplace_add_to_cart($product_id);

        // Gjej faqen e shportës
        $cart_page = get_page_by_path('cart');
        if ($cart_page) {
            wp_redirect(get_permalink($cart_page->ID));
            exit;
        }
    }

    // Hiq produkt
    if (isset($_GET['remove_from_cart'])) {
        $product_id = (int) $_GET['remove_from_cart'];
        marketplace_remove_from_cart($product_id);

        // Rifresko faqen e shportës
        $cart_page = get_page_by_path('cart');
        if ($cart_page) {
            wp_redirect(get_permalink($cart_page->ID));
            exit;
        }
    }
}
add_action('init', 'marketplace_handle_cart_actions');


/* ---------------------------------------------------------------
   11️⃣ CART PAGE SHORTCODE
---------------------------------------------------------------- */
function marketplace_cart_page_shortcode() {
    $items = marketplace_get_cart_items();
    global $products;

    if (empty($items)) {
        return '<p>Shporta është bosh.</p>';
    }

    $html = '<div class="cart-page">';
    $html .= '<h2>Shporta juaj</h2>';
    $html .= '<table class="cart-table">';
    $html .= '<tr><th>Produkt</th><th>Sasia</th><th>Çmimi</th><th>Totali</th><th></th></tr>';

    foreach ($items as $id => $qty) {
        if (isset($products[$id])) {
            $p = $products[$id];
            $subtotal = $p['price'] * $qty;

            $html .= '<tr>
                <td>
                    <img src="' . esc_url($p['image']) . '" alt="' . esc_attr($p['title']) . '" style="width:60px; height:auto; border-radius:5px; margin-right:10px; vertical-align:middle;">
                    ' . esc_html($p['title']) . '
                </td>
                <td>' . intval($qty) . '</td>
                <td>$' . esc_html($p['price']) . '</td>
                <td>$' . esc_html($subtotal) . '</td>
                <td><a href="?remove_from_cart=' . intval($id) . '" class="remove-btn">✖</a></td>
            </tr>';
        }
    }

    $total = marketplace_get_cart_total();
    $html .= '<tr><td colspan="3" style="text-align:right;"><strong>Totali:</strong></td><td><strong>$' . esc_html($total) . '</strong></td><td></td></tr>';
    $html .= '</table>';

    $html .= '<a href="' . site_url('/') . '" class="btn">↩ Vazhdo Blerjen</a> ';
    $html .= '<a href="' . site_url('/checkout') . '" class="btn btn-primary">💳 Vazhdo në Pagesë</a>';

    $html .= '</div>';
    return $html;
}
add_shortcode('cart_page', 'marketplace_cart_page_shortcode');


/* ---------------------------------------------------------------
   12️⃣ ADMIN FOOTER TEXT
---------------------------------------------------------------- */
function marketplace_custom_admin_footer() {
    echo 'Marketplace Theme © ' . date('Y') . ' | Krijuar me 💙 nga ChatGPT';
}
add_filter('admin_footer_text', 'marketplace_custom_admin_footer');


/* ---------------------------------------------------------------
   13️⃣ SECURITY & PERFORMANCE
---------------------------------------------------------------- */
function marketplace_remove_wp_version() {
    return '';
}
add_filter('the_generator', 'marketplace_remove_wp_version');

// Disable emojis
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Remove WP version from CSS/JS URLs
function marketplace_remove_script_version($src) {
    return remove_query_arg('ver', $src);
}
add_filter('style_loader_src', 'marketplace_remove_script_version', 9999);
add_filter('script_loader_src', 'marketplace_remove_script_version', 9999);

?>
