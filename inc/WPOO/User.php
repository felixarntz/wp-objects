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

class User extends Item {

	protected static $item_type = 'user';

	protected static $item_id_field_name = 'ID';

	protected static function get_item( $id = null ) {
		if ( is_object( $id ) && is_a( $id, 'WP_User' ) ) {
			return $id;
		}

		if ( null === $id ) {
			$id = get_current_user_id();

			if ( 1 > $id ) {
				$id = null;
			}
		}

		if ( is_numeric( $id ) ) {
			return get_user_by( 'id', $id );
		}

		return null;
	}

	protected function __construct( $item ) {
		parent::__construct( $item );
	}

	public function get_data( $field, $formatted = false ) {
		$data = null;

		if ( isset( $this->item->$field ) ) {
			$data = $this->item->$field;
		} elseif ( strpos( $field, 'user_' ) !== 0 ) {
			$field = 'user_' . $field;
			if ( isset( $this->item->$field ) ) {
				$data = $this->item->$field;
			}
		}

		//TODO: formatting

		return $data;
	}

	public function get_meta( $field = '', $single = false, $formatted = false ) {
		$use_wpud = null === $single || $formatted;
		if ( $field ) {
			if ( $use_wpud && function_exists( 'wpud_get_user_meta_value' ) ) {
				return wpud_get_user_meta_value( $this->item->ID, $field, $single, $formatted );
			}
			if ( ! $single ) {
				$single = false;
			}
			return get_user_meta( $this->item->ID, $field, $single );
		} else {
			if ( $use_wpud && function_exists( 'wpud_get_user_meta_values' ) ) {
				return wpud_get_user_meta_values( $this->item->ID, $single, $formatted );
			}
			return get_user_meta( $this->item->ID );
		}
	}

	public function get_role( $output = 'name' ) {
		if ( 'object' === $output ) {
			return get_role( $this->item->roles[0] );
		}
		return $this->item->roles[0];
	}

	public function get_url() {
		return get_author_posts_url( $this->item->ID );
	}
}
