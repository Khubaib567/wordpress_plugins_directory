<?php

 // Add admin menu page
  function custom_add_admin_menu() {
      add_menu_page(
          __('Custom Plugin Setttings', 'my-plugin-text-domain'),
          __('Custom', 'my-plugin-text-domain'),
          'manage_options',
          'custom-settings',
          'custom_options_page',
          'dashicons-superhero',
          10
      );
  }
add_action('admin_menu', 'custom_add_admin_menu');