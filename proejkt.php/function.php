<?php
// Regjistrimi i menuse
function register_my_menus() {
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'car-sales'),
    ));
}
add_action('init', 'register_my_menus');

// Enqueue style
function car_sales_enqueue_styles() {
    wp_enqueue_style('car-sales-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'car_sales_enqueue_styles');

// Custom Post Type: Car
function create_car_post_type() {
    $labels = array(
        'name' => 'Veturat',
        'singular_name' => 'Veturë',
        'menu_name' => 'Veturat',
        'add_new' => 'Shto Veturë',
        'add_new_item' => 'Shto Veturë të Re',
        'edit_item' => 'Ndrysho Veturën',
        'new_item' => 'Veturë e Re',
        'view_item' => 'Shiko Veturën',
        'search_items' => 'Kërko Veturat',
        'not_found' => 'Nuk u gjet asnjë veturë',
        'not_found_in_trash' => 'Nuk u gjet asnjë veturë në koshin e plehrave',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'veturat'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
    );

    register_post_type('car', $args);
}
add_action('init', 'create_car_post_type');

// Meta Boxes per detajet e vetures
function car_custom_meta_box() {
    add_meta_box('car_details', 'Detajet e Veturës', 'car_meta_box_callback', 'car', 'normal', 'high');
}

function car_meta_box_callback($post) {
    wp_nonce_field('car_save_meta_box_data', 'car_meta_box_nonce');

    $price = get_post_meta($post->ID, '_car_price', true);
    $year = get_post_meta($post->ID, '_car_year', true);
    $km = get_post_meta($post->ID, '_car_km', true);

    echo '<label for="car_price">Çmimi (€): </label>';
    echo '<input type="number" id="car_price" name="car_price" value="' . esc_attr($price) . '" size="25" />';
    
    echo '<br><br><label for="car_year">Viti: </label>';
    echo '<input type="number" id="car_year" name="car_year" value="' . esc_attr($year) . '" size="25" />';
    
    echo '<br><br><label for="car_km">Kilometrazha: </label>';
    echo '<input type="number" id="car_km" name="car_km" value="' . esc_attr($km) . '" size="25" />';
}

function car_save_meta_box_data($post_id) {
    if (!isset($_POST['car_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['car_meta_box_nonce'], 'car_save_meta_box_data')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['car_price'])) {
        update_post_meta($post_id, '_car_price', sanitize_text_field($_POST['car_price']));
    }
    if (isset($_POST['car_year'])) {
        update_post_meta($post_id, '_car_year', sanitize_text_field($_POST['car_year']));
    }
    if (isset($_POST['car_km'])) {
        update_post_meta($post_id, '_car_km', sanitize_text_field($_POST['car_km']));
    }
}
add_action('add_meta_boxes', 'car_custom_meta_box');
add_action('save_post', 'car_save_meta_box_data');
