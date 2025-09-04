<?php
/**
 * DS Theme complete with Bootstrap via CDN
 */

// Enqueue styles & scripts (Bootstrap 5 CDN + theme CSS)
function ds_enqueue_assets() {
  // Bootstrap CSS
  wp_enqueue_style( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' );

  // Main stylesheet
  wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '1.2', 'all' );

  // Bootstrap JS (bundle includes Popper) in footer
  wp_enqueue_script( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true );

  // Threaded comments only where needed
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets' );

/**
 * Register theme features like menus and supports
 * (Using init to match your lesson flow; after_setup_theme is also fine.)
 */
function ds_setup() {
  add_theme_support( 'menus' );
  register_nav_menu( 'primary', 'Primary Navigation' );
  register_nav_menu( 'footer', 'Footer Navigation' );

  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video' ) );
}

function ds_widgets_init(){
  register_sidebar( array(
    'name' =>('Primary Sidebar','ds theme'),
    'id' =>'primary',
    'description' => __('Main Sidebar that appers on the right','ds theme'),
    'class' => 'side-primary',
    'before_widget' => '<aside id="%1$" class="widget %2$s>',
    'after_widget' => '</aside>',
    'before_title' =>  '<h3 class="widget-title">',
    'after_title' => '</h3>',


  ) );
}
add_action( 'init', 'ds_setup' );
