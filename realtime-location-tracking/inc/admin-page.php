<?php

namespace MyCustomPlugin\Admin; // Define your namespace here

function custom_plugin_callback() {
    // Check user capabilities (security best practice)
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); 
        
        settings_fields( 'custom_plugin' );
        do_settings_sections( 'custom_plugin' );

        submit_button( __('Save Settings' , 'custom_plugin') );
        
        ?></h1>
        <p>Welcome to your custom WordPress admin page!</p>
    </div>
    <?php
}


function submenu_callback() {
    // Check user capabilities (security best practice)
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); 
        
        settings_fields( 'submenu_plugin' );
        do_settings_sections( 'submenu_plugin' );

        submit_button( __('Save Settings' , 'submenu_plugin') );
        
        ?></h1>
        <p>Welcome to your custom WordPress admin page!</p>
    </div>

    <?php
}

