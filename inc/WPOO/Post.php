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

class Post extends Item {

	protected static $item_type = 'post';

	protected static $item_id_field_name = 'ID';

	protected static function get_item( $id = null ) {
		if ( is_object( $id ) && is_a( $id, 'WP_Post' ) ) {
			return $id;
		}

		return get_post( $id );
	}

	protected $post_type = '';

	protected $meta = array();

	protected function __construct( $item ) {
		parent::__construct( $item );
	}

	public function get_data( $field, $formatted = false ) {
		$data = null;

		if ( isset( $this->item->$field ) ) {
			$data = $this->item->$field;
		} elseif ( strpos( $field, 'post_' ) !== 0 ) {
			$field = 'post_' . $field;
			if ( isset( $this->item->$field ) ) {
				$data = $this->item->$field;
			}
		}

		if ( $data && $formatted ) {
			switch ( $field ) {
				case 'post_content':
					global $post;

					if ( $post->ID === $this->item->ID ) {
						ob_start();
						the_content();
						$data = ob_get_clean();
					} else {
						$old_post = $post;

						$post = $this->item;
						setup_postdata( $post );

						ob_start();
						the_content();
						$data = ob_get_clean();

						$post = $old_post;
						wp_reset_postdata();
					}
					break;
				case 'post_excerpt':
					global $post;

					if ( $post->ID === $this->item->ID ) {
						ob_start();
						the_excerpt();
						$data = ob_get_clean();
					} else {
						$old_post = $post;

						$post = $this->item;
						setup_postdata( $post );

						ob_start();
						the_excerpt();
						$data = ob_get_clean();

						$post = $old_post;
						wp_reset_postdata();
					}
					break;
				case 'post_title':
					$data = get_the_title( $this->item->ID );
					break;
				case 'post_date':
				case 'post_modified':
				case 'post_date_gmt':
				case 'post_modified_gmt':
					if ( is_string( $formatted ) ) {
						switch ( $formatted ) {
							case 'timestamp':
								$data = strtotime( $this->item->$field );
								break;
							case 'human':
								$date = strtotime( $this->item->$field );
								$now = current_time( 'timestamp' );
								if ( $now < $date ) {
									$data = sprintf( __( 'in %s', 'wp-objects' ), human_time_diff( $now, $date ) );
								} else {
									$data = sprintf( __( '%s ago', 'wp-objects' ), human_time_diff( $date, $now ) );
								}
								break;
							case 'datetime':
								$data = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $this->item->$field ) );
								break;
							case 'date':
								$data = date_i18n( get_option( 'date_format' ), strtotime( $this->item->$field ) );
								break;
							case 'time':
								$data = date_i18n( get_option( 'time_format' ), strtotime( $this->item->$field ) );
								break;
							default:
								$data = date_i18n( $formatted, strtotime( $this->item->$field ) );
						}
					} else {
						$data = date_i18n( get_option( 'date_format' ), strtotime( $this->item->$field ) );
					}
					break;
				case 'guid':
					$data = get_the_guid( $this->item );
					break;
				default:
			}
		}

		return $data;
	}

	public function get_meta( $field = '', $single = false, $formatted = false ) {
		$use_wpptd = null === $single || $formatted;
		if ( $field ) {
			if ( $use_wpptd && function_exists( 'wpptd_get_post_meta_value' ) ) {
				return wpptd_get_post_meta_value( $this->item->ID, $field, $single, $formatted );
			}
			if ( ! $single ) {
				$single = false;
			}
			return get_post_meta( $this->item->ID, $field, $single );
		} else {
			if ( $use_wpptd && function_exists( 'wpptd_get_post_meta_values' ) ) {
				return wpptd_get_post_meta_values( $this->item->ID, $single, $formatted );
			}
			return get_post_meta( $this->item->ID );
		}
	}

	public function get_post_type( $output = 'name' ) {
		if ( 'object' === $output ) {
			return get_post_type_object( $this->item->post_type );
		}
		return $this->item->post_type;
	}

	public function get_url() {
		if ( 'attachment' === $this->item->post_type ) {
			return get_attachment_link( $this->item );
		}
		return get_permalink( $this->item );
	}

	public function get_taxonomies( $output = 'names' ) {
		return get_object_taxonomies( $this->item, $output );
	}

	public function get_terms( $taxonomy ) {
		return get_the_terms( $this->item, $taxonomy );
	}

	public function get_author() {
		if ( ! $this->supports( 'author' ) ) {
			return null;
		}
		return get_user_by( 'id', $this->item->post_author );
	}

	public function has_post_thumbnail() {
		return has_post_thumbnail( $this->item );
	}

	public function get_post_thumbnail( $size = 'post-thumbnail', $attr = '' ) {
		return get_the_post_thumbnail( $this->item, $size, $attr );
	}

	public function get_post_thumbnail_id() {
		return get_post_thumbnail_id( $this->item );
	}

	public function get_post_thumbnail_url( $size = 'post-thumbnail' ) {
		return get_the_post_thumbnail_url( $this->item, $size );
	}

	public function get_comments( $type = 'all' ) {
		if ( ! $this->supports( 'comments' ) ) {
			return array();
		}

		return get_comments( array(
			'post_id'	=> $this->item->id,
			'type'		=> $type,
		) );
	}

	public function get_comments_number( $formatted = false ) {
		global $post;

		if ( $formatted ) {
			if ( $post->ID === $this->item->ID ) {
				return get_comments_number_text();
			} else {
				$old_post = $post;

				$post = $this->item;
				$output = get_comments_number_text();
				$post = $old_post;

				return $output;
			}
		}
		return get_comments_number( $this->item );
	}

	public function get_comments_url() {
		return get_comments_link( $this->item );
	}

	public function get_post_class( $class = '', $formatted = false ) {
		$classes = get_post_class( $class, $this->item );
		if ( $formatted ) {
			return 'class="' . join( ' ', $classes ) . '"';
		}
		return $classes;
	}

	public function is_sticky() {
		return is_sticky( $this->item->ID );
	}

	public function supports( $feature ) {
		return post_type_supports( $this->item->post_type, $feature );
	}
}
