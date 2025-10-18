<?php
add_action('init', function () {
    $labels = [
        'name' => __('Projects', 'blearti21'),
        'singular_name' => __('Project', 'blearti21'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ];

    register_post_type('project', $args);
});
