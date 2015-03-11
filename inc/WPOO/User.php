<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPOO;

class User extends Item {

	protected static $item_type = 'user';

	protected static $item_id_field_name = 'ID';

	protected static function getItem( $id = null ) {
		global $wpdb;

		if ( $id === null ) {
			$id = get_current_user_id();

			if ( $id < 1 ) {
				$id = null;
			}
		}

		if ( $id !== null ) {
			return get_user_by( 'id', $id );
		}

		return null;
	}

	protected $role = '';

	protected function __construct( $item ) {
		parent::__construct();

		if ( $this->role == '' ) {
			$this->role = $this->item->roles[0];
		}
	}
}
