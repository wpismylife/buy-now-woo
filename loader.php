<?php
/**
 * The loader file.
 *
 * @package Buy_Now_Woo
 */

/**
 * First, we need autoload via Composer to make everything works.
 */
require_once trailingslashit( __DIR__ ) . 'vendor/autoload.php';

/**
 * Then, require the main class.
 */
require_once trailingslashit( __DIR__ ) . 'includes/functions.php';
require_once trailingslashit( __DIR__ ) . 'includes/Plugin.php';

/**
 * Alias the class "Buy_Now_Woo\Plugin" to "Buy_Now_Woo".
 */
class_alias( \Buy_Now_Woo\Plugin::class, 'Buy_Now_Woo', false );
