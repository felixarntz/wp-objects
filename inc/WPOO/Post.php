<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

namespace WPOO;

class Post extends Item {

	protected static $item_type = 'post';

	protected static $item_id_field_name = 'ID';

	protected static function getItem( $id = null ) {
		return get_post( $id );
	}

	protected $post_type = '';

	protected $meta = array();

	protected function __construct( $item ) {
		parent::__construct();

		if ( $this->post_type == '' ) {
			$this->post_type = $this->item->post_type;
		}

		$this->meta = get_post_meta( $this->id );
	}
}
