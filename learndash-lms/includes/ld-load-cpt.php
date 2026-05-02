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

    register_taxonomy('tab_category', 'custom_tab', [
        'label'        => 'Tab Categories',
        'public'       => true,
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'tab-category'],
    ]);
});


// STEP 02 : Shared Render Function (Reusable)
function get_custom_tab_content() {

    // Get all categories (tabs)
    $terms = get_terms([
        'taxonomy' => 'tab_category',
        'hide_empty' => true,
    ]);

    if (empty($terms)) {
        return '<p>No tabs found.</p>';
    }

    ob_start();
    ?>

     <!-- Tabs -->
    <div class="tab-filters">
        <button class="tab-btn active" data-filter="all">All</button>

        <?php foreach ($terms as $term): ?>
            <button class="tab-btn" data-filter="<?php echo esc_attr($term->slug); ?>">
                <?php echo esc_html($term->name); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Content -->
    <div class="tab-content">

        <?php
        $posts = get_posts([
            'post_type'   => 'custom_tab',
            'numberposts' => -1
        ]);

        foreach ($posts as $post):

            $post_terms = wp_get_post_terms($post->ID, 'tab_category');
            $term_slugs = wp_list_pluck($post_terms, 'slug');
            ?>

            <div class="tab-item" data-category="<?php echo esc_attr(implode(' ', $term_slugs)); ?>">
                <h3><?php echo esc_html($post->post_title); ?></h3>
                <?php echo apply_filters('the_content', $post->post_content); ?>
            </div>

        <?php endforeach; ?>

    </div>

    <!-- JS Filter -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const buttons = document.querySelectorAll('.tab-btn');
        const items = document.querySelectorAll('.tab-item');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {

                // Active class toggle
                buttons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                items.forEach(item => {

                    if (filter === 'all') {
                        item.style.display = 'block';
                    } else {
                        const categories = item.dataset.category;

                        if (categories.includes(filter)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    }

                });

            });
        });

    });
    </script>

    <style>
        .tab-filters {
            margin-bottom: 20px;
        }
        .tab-btn {
            margin-right: 10px;
            padding: 8px 12px;
            cursor: pointer;
        }
        .tab-btn.active {
            background: #000;
            color: #fff;
        }
        .tab-item {
            margin-bottom: 20px;
        }
    </style>

    <?php

    return ob_get_clean();
};


// STEP 03 : LearnDash Tab ( For Legacy Pages)
// add_filter('learndash_course_tabs', function ($tabs) {

//     $tabs['custom_tab'] = [
//         'title'    => 'My Custom Tab',
//         'priority' => 30,
//         'callback' => function() {
//             echo get_custom_tab_content();
//         }
//     ];

//     return $tabs;
// });


// STEP 04 : Shortcode for Front Page
add_shortcode('custom_tab_filter', function () {
    return get_custom_tab_content();
});