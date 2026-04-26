<?php

function wporg_settings_init() {

    $defaults = array(
                'type'              => 'string',
                'group'             => 'custom_plugin',
                'label'             => '',
                'description'       => '',
                'sanitize_callback' => null,
                'show_in_rest'      => false,
    );
    register_setting( 'custom_plugin' , 'custom_setting_filed_textbox'  , $defaults );
    register_setting( 'submenu_plugin' , 'custom_setting_filed_checkbox'  , $defaults );


    // Section 01 Settings
    add_settings_section( 'custom_plugin_settings_general', 'General Settings', 'custom_plugin_section_general_callback', 'custom_plugin');
    add_settings_field( 'custom_plugin_settings_general_field_1', 'Field 01 ', 'custom_plugin_section_general_callback_field' , 'custom_plugin', 'custom_plugin_settings_general'  );


    // Section 02 Settings
    add_settings_section( 'custom_plugin_settings_misc', 'Mics Settings', 'custom_plugin_section_misc_callback', 'submenu_plugin');
    add_settings_field( 'custom_plugin_settings_general_field_2', 'Checkbox', 'custom_plugin_section_general_callback_checkbox', 'submenu_plugin', 'custom_plugin_settings_misc' );



}

add_action('admin_init' , 'wporg_settings_init');


function custom_plugin_section_general_callback( ){
    echo '<p> Manage Main Page Settings.</p>';
}


function custom_plugin_section_misc_callback( ){
    echo '<p> Manage Misc Page Settings.</p>';
}


function custom_plugin_section_general_callback_field( ){

    $value = get_option( 'custom_setting_filed_textbox' );
    ?>
    <input type="text" name="custom_setting_filed_textbox" value="<?php echo isset($value) ? esc_attr($value) : '' ; ?> ">
    <?php
}


function custom_plugin_section_general_callback_checkbox( ){

   $checked_value = get_option('custom_setting_filed_checkbox');

   ?>

    <input type="checkbox" name="custom_setting_filed_checkbox" id="custom_setting_filed_checkbox" , <?php echo ($checked_value === "on"  ? 'checked' : '' )?>>

   <?php




}