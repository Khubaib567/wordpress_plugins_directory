<?php

if (!defined('MSR_PLUGIN_VERSION')) {
	define('MSR_PLUGIN_VERSION' , '1.0.0');
}

class Custom_Plugin {
    
    public function __construct() {

        // 1. Load files first
        $this->load_dependencies();

        add_action('admin_menu' , [$this , 'custom_plugin_menu']);
        add_action('init', [$this , 'register_project_post_type'] , 0);
        add_action('init', [$this , 'load_shortcodes']);

        // add_action('admin_enqueue_scripts' , [$this , 'wp_admin_scripts']);
        add_action('wp_enqueue_scripts' , [$this , 'wp_public_scripts']);
    }


    public function load_dependencies() {
        require_once MSR_PLUGIN_DIR_PATH . "./inc/custom-hooks.php";
        require_once MSR_PLUGIN_DIR_PATH . "./inc/taxonomies.php";
        require_once MSR_PLUGIN_DIR_PATH . "./inc/meta-boxes.php";
        // require_once MSR_PLUGIN_DIR_PATH . "./inc/voting.php";
        require_once MSR_PLUGIN_DIR_PATH . "./inc/dynamic-map.php";

    }

    public function load_shortcodes() {

        // $shortcodes = get_option('custom_shortcode_enable');

        // echo $shortcodes;
        // if ($shortcodes == "yes"){
        require_once MSR_PLUGIN_DIR_PATH . "./inc/shortcodes.php"; 
        // }

    }

    public function wp_admin_scripts() {

            // For Load the CSS
            wp_enqueue_style('custom_admin_style' , MSR_PLUGIN_URL_PATH . 'admin/css/admin.css' , '' , MSR_PLUGIN_VERSION );
            wp_enqueue_script('custom_admin_script' , MSR_PLUGIN_URL_PATH . 'admin/js/admin.js' , '' , MSR_PLUGIN_VERSION , true );
    }

    public function wp_public_scripts() {

            
    
            wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], null, true);
            wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');

            // For Load the CSS
            // wp_enqueue_style('custom_public_style' , MSR_PLUGIN_URL_PATH . 'public/css/public.css' , '' , MSR_PLUGIN_VERSION );
            // wp_enqueue_script('custom_public_script' , MSR_PLUGIN_URL_PATH . 'public/js/public.js' , '' , MSR_PLUGIN_VERSION , true );
            // wp_enqueue_script('custom_ajax_script' , MSR_PLUGIN_URL_PATH . 'ajax/js/ajax.js' , ['jquery'] , MSR_PLUGIN_VERSION , true );
            wp_enqueue_script('ajax_map_script', MSR_PLUGIN_URL_PATH . 'ajax/js/dynamic_map.js', ['jquery'],  MSR_PLUGIN_VERSION , true);

            // wp_localize_script( 'my_ajax_script', 'custom_ajax_2', ['ajax_url' => admin_url('admin-ajax.php'), 'nonce'    => wp_create_nonce('my_ajax_nonce')] );
            wp_localize_script( 'ajax_map_script', 'custom_ajax_3', ['ajax_url' => admin_url('admin-ajax.php'), 'nonce'    => wp_create_nonce('my_ajax_map')] );
    
    }


    // Register Custom Post Type
    public function register_project_post_type() {
        $labels = array(
            'name'                  => _x('Projects', 'Post Type General Name', 'my-plugin-text-domain'),
            'singular_name'         => _x('Project', 'Post Type Singular Name', 'my-plugin-text-domain'),
            'menu_name'            => __('Projects', 'my-plugin-text-domain'),
            'all_items'            => __('All Projects', 'my-plugin-text-domain'),
            'add_new_item'         => __('Add New Project', 'my-plugin-text-domain'),
            'add_new'              => __('Add New', 'my-plugin-text-domain'),
            'edit_item'            => __('Edit Project', 'my-plugin-text-domain'),
            'update_item'          => __('Update Project', 'my-plugin-text-domain'),
            'search_items'         => __('Search Project', 'my-plugin-text-domain'),
        );

        $args = array(
            'label'                 => __('Project', 'my-plugin-text-domain'),
            'labels'                => $labels,
            'supports'              => ["title","editor","thumbnail","excerpt" , "author" , "comments"],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_icon'             => 'dashicons-open-folder',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true
    );

        register_post_type('projects', $args);
    }

    // 1. Load the file
    public function custom_plugin_menu() {

        require_once plugin_dir_path(__FILE__) . 'admin-page.php';

        // Note the namespace inside the string:
        $main_callback = 'MyCustomPlugin\Admin\custom_plugin_callback';
        $sub_callback  = 'MyCustomPlugin\Admin\submenu_callback';


        add_menu_page( 
            "Custom Plugin",
            "Custom",
            "manage_options",
            "custom_plugin",
            $main_callback,
            "dashicons-text-page",
            20
        );

        add_submenu_page( "custom_plugin",
            "SubMenu Plugin", 
            "SubMenu Option", 
            "manage_options", 
            "submenu_slug",
            $sub_callback, 
            20
        );


        require_once MSR_PLUGIN_DIR_PATH . "./inc/admin-settings.php";

    }



    

}


new Custom_Plugin();