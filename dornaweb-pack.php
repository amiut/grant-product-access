<?php
/**
 * Plugin Name: Woocommerce Grant Product access
 * Description: Just enter an email address and choose a product, the plugin creates a user for it and makes a purchase for it
 * Plugin URI:  https://wwww.dornaweb.com
 * Version:     1.0
 * Author:      Dornaweb
 * Author URI:  https://wwww.dornaweb.com
 * License:     GPL
 */

defined('ABSPATH') || exit;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

if ( ! defined( 'DW_GACCESS_FILE' ) ) {
	define( 'DW_GACCESS_FILE', __FILE__ );
}

/**
 * Load core packages and the autoloader.
 * The SPL Autoloader needs PHP 5.6.0+ and this plugin won't work on older versions
 */
try{
	require __DIR__ . '/includes/class-autoloader.php';
} catch (Exception $err) {
    wp_die('Plugin Failed to load <br>' . $err->getMessage() . '<br> <a href="'. admin_url('plugins.php') .'">Go back</a>');
}

/**
 * Returns the main instance of WC.
 *
 * @since  1.0
 * @return DW_GACCESS\App
 */
function dw_gaccess() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	return DW_GACCESS\App::instance();
}

// Global for backwards compatibility.
$GLOBALS['dw_gaccess'] = dw_gaccess();
