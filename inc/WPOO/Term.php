<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPOO;

class Term extends Item {

	protected static $item_type = 'term';

	protected static $item_id_field_name = 'term_id';

	protected static function getItem( $id = null ) {
		global $wpdb;

		//TODO: get current term ID

		if ( $id !== null ) {
			$_taxonomy = $wpdb->get_row( $wpdb->prepare( "SELECT t.* FROM $wpdb->term_taxonomy AS t WHERE t.term_id = %d LIMIT 1", $id ) );

			return get_term( $id, $_taxonomy->taxonomy );
		}

		return null;
	}

	protected $taxonomy = '';

	protected function __construct( $item ) {
		parent::__construct();

		if ( $this->taxonomy == '' ) {
			$this->taxonomy = $this->item->taxonomy;
		}
	}
}
