<?php
/**
 * Plugin Name: Meta Pixel Server Side for WooCommerce
 * Description: Meta Pixel Server Side for WooCommerce
 * Version: 1.0.0
 * Author: Focus On
 * Author URI: https://github.com/Focus-On-Agency
 * Text Domain: focuson-mpsfw
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * WC requires at least: 9.0
 * WC tested up to: 9.0
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Tags: woocommerce, meta pixel, server side, tracking, facebook
 * Contributors: pcatapan
 * Donate link: https://donate.stripe.com/dR6dU04JV0kx1Z6dR6
 */

if (!defined('ABSPATH')) exit;

// Initialize the plugin
add_action('plugins_loaded', 'focuson_mpsfw_init');
add_action('before_woocommerce_init', 'focuson_mpsfw_declare_woocommerce_compatibility');

/**
 * Initialize the plugin
*/
function focuson_mpsfw_init()
{
	// Check plugin requirements
	if (!focuson_mpsfw_check_requirements()) return;

	// Load dependencies
	focuson_mpsfw_load_dependencies();

	// Load translations
	load_plugin_textdomain('focuson-mpsfw', false, dirname(plugin_basename(__FILE__)) . '/languages');

	// Boot the plugin
	(new \Focuson\MPSFW\PixelServerSide())->boot();
}

// $api = Api::init(null, null, $this->access_token[$pixel_Id],false);


/**
 * Check plugin requirements
*/
function focuson_mpsfw_check_requirements()
{
	// Check for Composer autoloader
	if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
		focuson_mpsfw_display_error_and_deactivate(__('Error loading plugin. Autoload not found.', 'focuson-mpsfw'));
		return false;
	}

	return true;
}

/**
 * Load plugin dependencies
*/
function focuson_mpsfw_load_dependencies()
{
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Display an error message and deactivate the plugin
*/
function focuson_mpsfw_display_error_and_deactivate($message)
{
	add_action('admin_notices', function() use ($message) {
		echo '<div class="notice notice-error"><p><strong>Woo Advanced Discounts</strong>: ' . esc_html($message) . '</p></div>';
	});

	if (function_exists('deactivate_plugins')) {
		deactivate_plugins(plugin_basename(__FILE__));
	}
}

/**
 * Declare compatibility with WooCommerce custom order tables
*/
function focuson_mpsfw_declare_woocommerce_compatibility()
{
	if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
	}
}