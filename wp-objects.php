<?php
/*
Plugin Name: WP Objects
Plugin URI: https://github.com/felixarntz/wp-objects/
Description: This plugin contains classes to handle WordPress posts, terms and users in an object-oriented approach.
Version: 1.0.0
Author: Felix Arntz
Author URI: http://leaves-and-love.net
License: GNU General Public License v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: wp-objects
Domain Path: /languages/
Tags: wordpress, plugin, muplugin, objects, oo, object-oriented, post, term, user
*/
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'WPOO_VERSION', '1.0.0' );

if ( file_exists( dirname( __FILE__ ) . '/wp-objects/languages/wp-objects.pot' ) ) {
	define( 'WPOO_MUPLUGIN', true );
} else {
	define( 'WPOO_MUPLUGIN', false );
}

function wpoo_init() {
	global $wp_version;

	if ( WPOO_MUPLUGIN ) {
		load_plugin_textdomain( 'wp-objects', false, dirname( plugin_basename( __FILE__ ) ) . '/wp-objects/languages/' );
	} else {
		load_plugin_textdomain( 'wp-objects', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	if ( 0 > version_compare( phpversion(), '5.3.0' ) ) {
		add_action( 'admin_notices', 'wpoo_display_phpversion_error_notice' );
		return;
	}

	if ( 0 > version_compare( $wp_version, '4.4.0' ) ) {
		add_action( 'admin_notices', 'wpoo_display_wpversion_error_notice' );
		return;
	}

	if ( WPOO_MUPLUGIN ) {
		if ( ! class_exists( 'WPOO\Item' ) && file_exists( dirname( __FILE__ ) . '/wp-objects/vendor/autoload.php' ) ) {
			require_once dirname( __FILE__ ) . '/wp-objects/vendor/autoload.php';
		}
	} else {
		if ( ! class_exists( 'WPOO\Item' ) && file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
			require_once dirname( __FILE__ ) . '/vendor/autoload.php';
		}
	}
}
add_action( 'plugins_loaded', 'wpoo_init' );

function wpoo_display_phpversion_error_notice() {
	echo '<div class="error">';
	echo '<p>' . sprintf( __( 'Fatal problem with %s:', 'wp-objects' ), '<strong>WP Objects</strong>' ) . '</p>';
	echo '<p>';
	printf( __( 'The plugin requires %1$s version %2$s. However, you are currently using version %3$s.', 'wp-objects' ), 'PHP', '5.3.0', phpversion() );
	echo '</p>';
	echo '</div>';
}

function wpoo_display_wpversion_error_notice() {
	global $wp_version;

	echo '<div class="error">';
	echo '<p>' . sprintf( __( 'Fatal problem with %s:', 'wp-objects' ), '<strong>WP Objects</strong>' ) . '</p>';
	echo '<p>';
	printf( __( 'The plugin requires %1$s version %2$s. However, you are currently using version %3$s.', 'wp-objects' ), 'WordPress', '4.4.0', $wp_version );
	echo '</p>';
	echo '</div>';
}
