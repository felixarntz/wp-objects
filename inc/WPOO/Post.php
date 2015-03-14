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
		if ( is_object( $id ) && is_a( $id, '\WP_Post' ) ) {
			return $id;
		}
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

	public function getPostData( $field, $filtered = false ) {
		$data = false;

		if ( $filtered ) {
			if ( $field == 'content' || $field == 'post_content' ) {
				global $post;
				$old_post = $post;

				$post = $this->item;
				setup_postdata( $post );
				ob_start();
				the_content();
				$content = ob_get_clean();
				$post = $old_post;
				wp_reset_postdata();
				return $content;
			} elseif ( $field == 'excerpt' || $field == 'post_excerpt' ) {
				global $post;
				$old_post = $post;

				$post = $this->item;
				setup_postdata( $post );
				ob_start();
				the_excerpt();
				$excerpt = ob_get_clean();
				$post = $old_post;
				wp_reset_postdata();
				return $excerpt;
			} elseif ( $field == 'title' || $field == 'post_title' ) {
				return get_the_title( $this->item->ID );
			} elseif ( in_array( $field, array( 'date', 'modified', 'date_gmt', 'modified_gmt') ) ) {

			}
		}

		if ( isset( $this->item->$field ) ) {
			$data = $this->item->$field;
		} elseif ( strpos( $field, 'post_' ) !== 0 ) {
			$field = 'post_' . $field;
			if ( isset( $this->item->$field ) ) {
				$data = $this->item->$field;
			}
		}

		if ( $data && $filtered ) {
			if ( $field == 'post_content' ) {
				global $post;
				$old_post = $post;

				$post = $this->item;
				setup_postdata( $post );
				ob_start();
				the_content();
				$data = ob_get_clean();
				$post = $old_post;
				wp_reset_postdata();
			} elseif ( $field == 'post_excerpt' ) {
				global $post;
				$old_post = $post;

				$post = $this->item;
				setup_postdata( $post );
				ob_start();
				the_excerpt();
				$data = ob_get_clean();
				$post = $old_post;
				wp_reset_postdata();
			} elseif ( $field == 'post_title' ) {
				$data = get_the_title( $this->item->ID );
			} elseif ( in_array( $field, array( 'post_date', 'post_modified', 'post_date_gmt', 'post_modified_gmt') ) ) {
				$dateformat = '';
				if ( is_string( $filtered ) ) {
					$dateformat = $filtered;
				} else {
					$dateformat = get_option( 'date_format' );
				}
				$data = date_i18n( $dateformat, strtotime( $this->item->$field ) );
			}
		}

		return $data;
	}
}
