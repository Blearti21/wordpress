<?php


function ds_enqueue_assets() {
  
  wp_enqueue_style( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' );


  wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '1.2', 'all' );


  wp_enqueue_script( 'bootstrap-cdn', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js', array(), null, true );


 
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
}
add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets' );

// In your theme's functions.php or plugin file:
function my_enqueue_scripts() {
    wp_enqueue_script('jquery');  // Enqueue WordPress jQuery
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');

function my_custom_jquery_script() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Add a new div element with text inside the body
        $('body').append('<div id="my-new-element">Hello, I am new!</div>');
    });
    </script>
    <?php
}
add_action('wp_footer', 'my_custom_jquery_script');


function ds_setup() {
  add_theme_support( 'menus' );
  register_nav_menu( 'primary', 'Primary Navigation' );
  register_nav_menu( 'footer', 'Footer Navigation' );


  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  add_theme_support( 'post-formats', array( 'aside', 'image', 'video' ) );
}
add_action( 'init', 'ds_setup' );