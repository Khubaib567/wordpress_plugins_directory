<?php

 // Register settings
  function custom_settings_init() {
      add_settings_section(
          'custom_options_section',
          __('Custom Plugin Setttings', 'my-plugin-text-domain'),
          function() {
              
          },
          'custom-settings'
      );
  
      
      add_settings_field(
          'custom_shortcode_txt',
          __('Default ShortCode Text', 'my-plugin-text-domain'),
          function() {
              $value = get_option('custom_shortcode_txt');
              ?>
              <input
              type="text"
              name="custom_shortcode_txt"
              id="custom_shortcode_txt"
              value="<?php echo esc_attr($value); ?>"
              class="regular-text"
              placeholder="This is a TEST Shortcode."
          />
              
              <?php
          },
          'custom-settings',
          'custom_options_section'
      );
  
      register_setting('custom_options', 'custom_shortcode_txt');

      add_settings_field(
          'custom_shortcode_enable',
          __('Enable Shortcode', 'my-plugin-text-domain'),
          function() {
              $value = get_option('custom_shortcode_enable');
              ?>
              <select
              name="custom_shortcode_enable"
              id="custom_shortcode_enable"
              class="regular-text"
          >
              <option value=""><?php _e('Select an option', 'my-plugin-text-domain'); ?></option>
              <?php
              // Example options - replace with actual options
              $options = array(
                  'yes' => __('Yes', 'my-plugin-text-domain'),
                  'no' => __('No', 'my-plugin-text-domain'),
              );
              
              foreach ($options as $option_value => $option_label) {
                  printf(
                      '<option value="%s" %s>%s</option>',
                      esc_attr($option_value),
                      selected($value, $option_value, false),
                      esc_html($option_label)
                  );
              }
              ?>
          </select>
              
              <?php
          },
          'custom-settings',
          'custom_options_section'
      );
  
      register_setting('custom_options', 'custom_shortcode_enable');
  }
  add_action('admin_init', 'custom_settings_init');