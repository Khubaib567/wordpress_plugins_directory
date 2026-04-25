<?php


// STEP 01 : Register Your Custom Post Type 

add_action('init', function () {
    register_post_type('custom_tab', [
        'label' => 'Custom Tabs',
        'public' => true,
        'supports' => ['title', 'editor'],
    ]);
});


// STEP 02 : Add Tab in LearnDash Interface

add_filter('learndash_course_tabs', function ($tabs) {

    $tabs['custom_tab'] = [
        'title'    => 'My Custom Tab',
        'priority' => 30,
        'callback' => 'render_custom_tab_content'
    ];

    return $tabs;
});


// STEP 03 : Render Your Custom Tab Content

function render_custom_tab_content($course_id, $user_id) {

    $posts = get_posts([
        'post_type' => 'custom_tab',
        'numberposts' => -1
    ]);

    echo '<div class="custom-tab-wrapper">';

    foreach ($posts as $post) {
        echo '<h3>' . esc_html($post->post_title) . '</h3>';
        echo apply_filters('the_content', $post->post_content);
    }

    echo '</div>';
}


