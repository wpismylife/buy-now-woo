<?php
/**
 * Plugin Name:     Buy Now Button for WooCommerce
 * Plugin URI:      https://wpismylife.com/
 * Description:     Buy your product only one step in the Product Detail page.
 * Author:          wpismylife
 * Author URI:      https://wpismylife.com/contact-me/
 * Text Domain:     buy-now-woo
 * Domain Path:     /languages
 * Version:         1.0.0
 * WC requires at least: 3.0.0
 * WC tested up to: 4.0
 *
 * @package         Woo_Buy_Now
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * WooCommerce Simple Buy Now only works with WordPress 4.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.9', '<' ) ) {
	/**
	 * Prints an update nag after an unsuccessful attempt to active
	 * WooCommerce Simple Buy Now on WordPress versions prior to 4.6.
	 *
	 * @global string $wp_version WordPress version.
	 */
	function buy_now_woo_wordpress_upgrade_notice() {
		$message = sprintf( esc_html__( 'WooCommerce Buy Now requires at least WordPress version 4.6, you are running version %s. Please upgrade and try again!', 'buy-now-woo' ), $GLOBALS['wp_version'] );
		printf( '<div class="error"><p>%s</p></div>', $message ); // WPCS: XSS OK.

		deactivate_plugins( [ 'buy_now_woo/buy_now_woo.php' ] );
	}

	add_action( 'admin_notices', 'buy_now_woo_wordpress_upgrade_notice' );

	return;
}

/**
 * And only works with PHP 5.4 or later.
 */
if ( version_compare( phpversion(), '5.4', '<' ) ) {
	/**
	 * Adds a message for outdate PHP version.
	 */
	function buy_now_woo_php_upgrade_notice() {
		$message = sprintf( esc_html__( 'WooCommerce Simple Buy Now requires at least PHP version 5.4 to work, you are running version %s. Please contact to your administrator to upgrade PHP version!', 'buy-now-woo' ), phpversion() );
		printf( '<div class="error"><p>%s</p></div>', $message ); // WPCS: XSS OK.

		deactivate_plugins( [ 'buy_now_woo/buy_now_woo.php' ] );
	}

	add_action( 'admin_notices', 'buy_now_woo_php_upgrade_notice' );

	return;
}

if ( defined( 'BUY_NOW_WOO_VERSION' ) ) {
	return;
}

define( 'BUY_NOW_WOO_VERSION', '1.0.0' );
define( 'BUY_NOW_WOO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUY_NOW_WOO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Admin notice: Require WooCommerce.
 */
function buy_now_woo_admin_notice() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		/* translators: 1. URL link. */
		echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Buy Now for WooCommerce requires WooCommerce to be installed and active. You can download %s here.', 'buy-now-woo' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
	}
}

// Include the loader.
require_once dirname( __FILE__ ) . '/loader.php';

add_action( 'plugins_loaded', function () {
	if ( class_exists( 'WooCommerce' ) ) {
		$GLOBALS['buy_now_woo'] = Buy_Now_Woo::get_instance();
	}
	add_action( 'admin_notices', 'buy_now_woo_admin_notice', 4 );
} );
