<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPOO;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WPOO\Item' ) ) {

	abstract class Item {

		protected static $items = array();

		protected static $item_type = '';

		protected static $item_id_field_name = '';

		public static function get( $id = null ) {

			$item = null;

			if ( ! isset( self::$items[ static::$item_type ] ) ) {
				self::$items[ static::$item_type ] = array();
			}

			if ( $id === null ) {
				$item = static::get_item( $id );

				if ( $item !== null ) {
					$id_field_name = static::$item_id_field_name;
					if ( isset( $item->$id_field_name ) ) {
						$id = $item->$id_field_name;
					}
				}
			}

			if ( $id !== null ) {
				if ( ! isset( self::$items[ static::$item_type ][ $id ] ) ) {
					if ( $item === null ) {
						$item = static::get_item( $id );
					}

					if ( $item !== null ) {
						self::$items[ static::$item_type ][ $id ] = new static( $item );
					} else {
						return null;
					}
				}

				return self::$items[ static::$item_type ][ $id ];
			}

			return null;
		}

		protected static function get_item( $id = null ) {
			return null;
		}

		protected $item = null;

		protected function __construct( $item ) {
			$this->item = $item;
		}

		public function get_ID() {
			$id_field_name = static::$item_id_field_name;
			return absint( $item->$id_field_name );
		}

		public abstract function get_data( $field, $formatted = false );

		public abstract function get_meta( $field = '', $single = null, $formatted = false );
	}

}
