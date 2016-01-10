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

if ( ! class_exists( 'WPOO\Term' ) ) {

	class Term extends Item {

		protected static $item_type = 'term';

		protected static $item_id_field_name = 'term_id';

		protected static function get_item( $id = null ) {
			if ( is_object( $id ) && is_a( $id, 'WP_Term' ) ) {
				return $id;
			}

			if ( null === $id ) {
				if ( is_category() || is_tag() || is_tax() ) {
					$id = get_queried_object();
				} elseif ( is_singular() ) {
					$post = get_queried_object();
					$taxonomies = get_object_taxonomies( $post );
					if ( count( $taxonomies ) > 0 ) {
						$terms = get_the_terms( $post->ID, $taxonomies[0] );
						if ( is_array( $terms ) && count( $terms ) > 0 ) {
							$id = $terms[0];
						}
					}
				}
			}

			if ( is_numeric( $id ) ) {
				return get_term( $id );
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
			} elseif ( strpos( $field, 'term_' ) !== 0 ) {
				$field = 'term_' . $field;
				if ( isset( $this->item->$field ) ) {
					$data = $this->item->$field;
				}
			}

			//TODO: formatting

			return $data;
		}

		public function get_meta( $field = '', $single = null, $formatted = false ) {
			if ( $field ) {
				if ( function_exists( 'wpptd_get_term_meta_value' ) ) {
					return wpptd_get_term_meta_value( $this->item->ID, $field, $single, $formatted );
				}
				if ( ! $single ) {
					$single = false;
				}
				return get_term_meta( $this->item->ID, $field, $single );
			} else {
				if ( function_exists( 'wpptd_get_term_meta_values' ) ) {
					return wpptd_get_term_meta_values( $this->item->ID, $single, $formatted );
				}
				return get_term_meta( $this->item->ID );
			}
		}

		public function get_taxonomy( $output = 'name' ) {
			if ( 'object' === $output ) {
				return get_taxonomy( $this->item->taxonomy );
			}
			return $this->item->taxonomy;
		}
	}

}
