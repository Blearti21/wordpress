<?php
function ds_enqueue_script(){
    wp_enqueue_style('main-style', get_template_dirctory_uri()."/style.css",array());
}

add_action('wp_enqueue_script','ds_enqueue_script');

function ds_setup(){
    add_theme_support('menus');
    register_nav_menu('primary','Primary Navigation');
}
add_action('init','ds_setup');
?>