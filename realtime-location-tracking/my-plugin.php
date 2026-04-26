<?php
/**
 * Plugin Name: my-plugin
 * Plugin URI:  https://my-plugin.com

 * Description: My Plugin Description
 * Version:     1.0.0
 * Author:      Khubaib Ahmed
 * Author URI:  https://my-plugin.com

 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: my-plugin-text-domain
 * Domain Path: /languages

 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}


if (!defined('MSR_PLUGIN_DIR_PATH')) {
	define('MSR_PLUGIN_DIR_PATH' , plugin_dir_path(__FILE__));
}

if( !defined('MSR_PLUGIN_URL_PATH')){
	define('MSR_PLUGIN_URL_PATH' , plugin_dir_url(__FILE__));
}


if( !defined('MSR_PLUGIN_DB_VERSION')) {
	define('MSR_PLUGIN_DB_VERSION' , '1.0.0');
}

// require_once MSR_PLUGIN_DIR_PATH . "./inc/db.php";	
// register_activation_hook( __FILE__ , 'plugin_reaction_table' );



require_once MSR_PLUGIN_DIR_PATH . "./inc/plugin.php";
// require_once MSR_PLUGIN_DIR_PATH . "./inc/shortcode-admin-menu.php";
// require_once MSR_PLUGIN_DIR_PATH . "./inc/shortcode-admin-setting.php";
// require_once MSR_PLUGIN_DIR_PATH . "./inc/shortcode-admin-page.php";





