<?php
/*
Plugin Name: WP Objects
Plugin URI: http://wordpress.org/plugins/wp-objects/
Description: This plugin contains classes to handle WordPress posts, terms and users in an object-oriented approach.
Version: 1.0.0
Author: Felix Arntz
Author URI: http://leaves-and-love.net
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wpoo
Domain Path: /languages/
Tags: wordpress, plugin, objects, oo, object-oriented
*/
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

define( 'WPOO_VERSION', '1.0.0' );
define( 'WPOO_REQUIRED_WP', '3.5' );
define( 'WPOO_REQUIRED_PHP', '5.3.0' );

define( 'WPOO_NAME', 'WP Objects' );
define( 'WPOO_MAINFILE', __FILE__ );
define( 'WPOO_BASENAME', plugin_basename( WPOO_MAINFILE ) );
define( 'WPOO_PATH', untrailingslashit( plugin_dir_path( WPOO_MAINFILE ) ) );
define( 'WPOO_URL', untrailingslashit( plugin_dir_url( WPOO_MAINFILE ) ) );

require_once WPOO_PATH . '/inc/functions.php';

define( 'WPOO_RUNNING', wpoo_version_check() );

function wpoo_init() {
	load_plugin_textdomain( 'wpoo', false, dirname( WPOO_BASENAME ) . '/languages/' );

	if ( WPOO_RUNNING > 0 ) {
		spl_autoload_register( 'wpoo_autoload', true, true );
	} else {
		add_action( 'admin_notices', 'wpoo_display_version_error_notice' );
	}
}
add_action( 'plugins_loaded', 'wpoo_init' );
