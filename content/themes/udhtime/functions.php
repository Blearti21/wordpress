<?php
/**
 * Udhtime Theme functions
 */

if (!defined('UDHTIME_VERSION')) {
    define('UDHTIME_VERSION', '1.0.0');
}

// Theme setup
function udhtime_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);

    register_nav_menus([
        'primary' => __('Primary Menu', 'udhtime'),
    ]);
}
add_action('after_setup_theme', 'udhtime_setup');

// Enqueue styles and scripts
function udhtime_enqueue_assets() {
    wp_enqueue_style('udhtime-style', get_stylesheet_uri(), [], UDHTIME_VERSION);
}
add_action('wp_enqueue_scripts', 'udhtime_enqueue_assets');

// Simple helper to trim excerpts
function udhtime_trim_excerpt($length = 20) {
    $excerpt = get_the_excerpt();
    $words = preg_split('/\s+/', wp_strip_all_tags($excerpt));
    if (count($words) <= $length) {
        return esc_html($excerpt);
    }
    $short = implode(' ', array_slice($words, 0, $length));
    return esc_html($short . '…');
}
