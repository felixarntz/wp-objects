<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPOO;

use LaL_WP_Plugin as Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPOO\App' ) ) {

	class App extends Plugin {

		protected static $_args = array();

		protected function __construct( $args ) {
			parent::__construct( $args );
		}

		protected function run() {}
	}
}
