<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPOO;

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
			$item = static::getItem( $id );

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
					$item = static::getItem( $id );
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

	protected static function getItem( $id = null ) {
		return null;
	}

	protected $id = 0;
	protected $item = null;

	protected function __construct( $item ) {
		$id_field_name = static::$item_id_field_name;
		$this->id = $item->$id_field_name;
		$this->item = $item;
	}

	public function getID() {
		return $this->id;
	}
}
