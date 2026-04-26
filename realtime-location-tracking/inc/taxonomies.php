<?php


// Register Custom Taxonomy
function register_product_industry_taxonomy() {
    $labels = array(
        'name'                       => _x('Industries', 'Taxonomy General Name', 'my-plugin-text-domain'),
        'singular_name'              => _x('Industry', 'Taxonomy Singular Name', 'my-plugin-text-domain'),
        'menu_name'                  => __('Industries', 'my-plugin-text-domain'),
        'all_items'                  => __('All Industries', 'my-plugin-text-domain'),
        'parent_item'                => __('Parent Industry', 'my-plugin-text-domain'),
        'parent_item_colon'          => __('Parent Industry:', 'my-plugin-text-domain'),
        'new_item_name'              => __('New Industry Name', 'my-plugin-text-domain'),
        'add_new_item'               => __('Add New Industry', 'my-plugin-text-domain'),
        'edit_item'                  => __('Edit Industry', 'my-plugin-text-domain'),
        'update_item'                => __('Update Industry', 'my-plugin-text-domain'),
        'view_item'                  => __('View Industry', 'my-plugin-text-domain'),
        'search_items'               => __('Search Industries', 'my-plugin-text-domain'),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'publicly_queryable'         => true,
        'show_ui'                    => true,
        'show_in_menu'               => true,
        'show_in_nav_menus'          => true,
        'show_in_rest'               => true,
        'rest_base'                  => 'product_industry',
        'show_tagcloud'              => true,
        'show_in_quick_edit'         => true,
        'show_admin_column'          => true,
    );

    register_taxonomy('product_industry', ["projects"], $args);
}
add_action('init', 'register_product_industry_taxonomy', 0);