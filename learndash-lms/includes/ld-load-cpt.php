<?php

// STEP 01 : Register Your Custom Post Type 
add_action('init', function () {
    register_post_type('custom_tab', [
        'label'        => 'Custom Tabs',
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => [
            'slug' => 'custom-tab',
        ],
        'supports'     => ['title', 'editor'],
    ]);
});


// STEP 02 : Shared Render Function (Reusable)
function get_custom_tab_content() {

    $posts = get_posts([
        'post_type'   => 'custom_tab',
        'numberposts' => -1
    ]);

    if (empty($posts)) {
        return '<p>No content found.</p>';
    }

    ob_start();

    echo '<div class="custom-tab-wrapper">';

    foreach ($posts as $post) {
        echo '<h3>' . esc_html($post->post_title) . '</h3>';
        echo apply_filters('the_content', $post->post_content);
    }

    echo '</div>';

    return ob_get_clean();
}


// STEP 03 : LearnDash Tab (still works inside courses)
add_filter('learndash_course_tabs', function ($tabs) {

    $tabs['custom_tab'] = [
        'title'    => 'My Custom Tab',
        'priority' => 30,
        'callback' => function() {
            echo get_custom_tab_content();
        }
    ];

    return $tabs;
});


// STEP 04 : Shortcode for Front Page
add_shortcode('custom_tab_content', function () {
    return get_custom_tab_content();
});