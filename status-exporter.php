<?php
if ( ! defined( 'ABSPATH' ) ) 
exit; // Exit if accessed directly

/**
 * Plugin Name: Status Exporter
 * Description: Export/Download all installed Plugins status in CSV file record.
 * Version: 1.1.0
 * Text Domain: status-exporter
 * Author: Pooja Nagvadia
 * Domain Path: languages
 * Tested up to: 6.6
 */


/**
 * Basic plugin definitions
 *
 * @package Status Exporter
 * @since 1.0
 */

if ( ! defined( 'STATUS_EXPORT_DIR' ) ) {
	define( 'STATUS_EXPORT_DIR', dirname( __FILE__ ) );      // Plugin dir
}

if ( ! defined( 'STATUS_EXPORT_VERSION' ) ) {
	define( 'STATUS_EXPORT_VERSION', '1.0.2' );      // Plugin Version
}

if ( ! defined( 'STATUS_EXPORT_URL' ) ) {
	define( 'STATUS_EXPORT_URL', plugin_dir_url( __FILE__ ) );   // Plugin url
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation.
 *
 * @package Status Exporter
 * @since 1.0
 */

load_plugin_textdomain( 'status-exporter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Activation Hook
 * Register plugin activation hook.
 *
 * @package Status Exporter
 * @since 1.0
 */

register_activation_hook( __FILE__, 'status_export_install' );



function status_export_install() {

}



/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 *
 * @package Status Exporter
 * @since 1.0
 */

register_deactivation_hook( __FILE__, 'status_export_uninstall' );



function status_export_uninstall() {

}


// Global variables

global $status_exporter_admin;



// Admin class handles most of admin functionalities of plugin

require_once STATUS_EXPORT_DIR . '/status-exporter-admin.php';

$status_exporter_admin = new Status_exporter_Admin();

$status_exporter_admin->add_hooks();
