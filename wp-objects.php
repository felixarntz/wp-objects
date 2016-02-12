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

if ( ! defined( 'ABSPATH' ) || defined( 'WPOO_VERSION' ) ) {
	die();
}

if ( version_compare( phpversion(), '5.3.0' ) >= 0 && ! class_exists( 'WPOO\App' ) ) {
	if ( file_exists( dirname( __FILE__ ) . '/wp-objects/vendor/autoload.php' ) ) {
		require_once dirname( __FILE__ ) . '/wp-objects/vendor/autoload.php';
	} elseif ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
		require_once dirname( __FILE__ ) . '/vendor/autoload.php';
	}
} elseif ( ! class_exists( 'LaL_WP_Plugin_Loader' ) ) {
	if ( file_exists( dirname( __FILE__ ) . '/wp-objects/vendor/felixarntz/leavesandlove-wp-plugin-util/leavesandlove-wp-plugin-loader.php' ) ) {
		require_once dirname( __FILE__ ) . '/wp-objects/vendor/felixarntz/leavesandlove-wp-plugin-util/leavesandlove-wp-plugin-loader.php';
	} elseif ( file_exists( dirname( __FILE__ ) . '/vendor/felixarntz/leavesandlove-wp-plugin-util/leavesandlove-wp-plugin-loader.php' ) ) {
		require_once dirname( __FILE__ ) . '/vendor/felixarntz/leavesandlove-wp-plugin-util/leavesandlove-wp-plugin-loader.php';
	}
}

LaL_WP_Plugin_Loader::load_plugin( array(
	'slug'				=> 'wp-objects',
	'name'				=> 'WP Objects',
	'version'			=> '1.0.0',
	'main_file'			=> __FILE__,
	'namespace'			=> 'WPOO',
	'textdomain'		=> 'wp-objects',
	'is_library'		=> true,
), array(
	'phpversion'		=> '5.3.0',
	'wpversion'			=> '4.4',
) );
