<?php
/*
 * Plugin Name: Resoc Social Image Beautifier for WooCommerce
 * Version: 0.0.5
 * Plugin URI: https://resoc.io/wordpress
 * Description: Beautiful product images on social networks
 * Author: Philippe Bernard
 * Author URI: https://resoc.io/
 * Requires at least: 4.0
 * Tested up to: 5.0
 *
 * Text Domain: resoc-social-image-beautifier-for-woocommerce
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author phbernard
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-resoc-sibfwc.php' );

// Load plugin libraries
require_once( 'includes/lib/class-resoc-sibfwc-admin.php' );
require_once( 'includes/lib/class-resoc-sibfwc-public.php' );

/**
 * Returns the main instance of Resoc_SIBfWC to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Resoc_SIBfWC
 */
function Resoc_SIBfWC () {
	$instance = Resoc_SIBfWC::instance( __FILE__, '0.0.5' );

	return $instance;
}

Resoc_SIBfWC();
