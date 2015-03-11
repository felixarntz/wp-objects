<?php
/**
 * @package WPOO
 * @version 1.0.0
 * @author Felix Arntz <felix-arntz@leaves-and-love.net>
 */

function wpoo_version_check() {
	global $wp_version;

	$ret = 1;
	if ( version_compare( $wp_version, WPOO_REQUIRED_WP ) < 0 ) {
		$ret -= 1;
	}
	if ( version_compare( phpversion(), WPOO_REQUIRED_PHP ) < 0 ) {
		$ret -= 2;
	}

	return $ret;
}

function wpoo_display_version_error_notice() {
	global $wp_version;

	echo '<div class="error">';
	echo '<p>' . sprintf( __( 'Fatal problem with %s', 'wpoo' ), '<strong>' . WPOO_NAME . ':</strong>' ) . '</p>';
	if ( WPOO_RUNNING != -1 ) {
		echo '<p>';
		printf( __( 'The plugin requires WordPress version %1$s. However, you are currently using version %2$s.', 'wpoo' ), WPOO_REQUIRED_WP, $wp_version );
		echo '</p>';
	}
	if ( WPOO_RUNNING != 0 ) {
		echo '<p>';
		printf( __( 'The plugin requires PHP version %1$s. However, you are currently using version %2$s.', 'wpoo' ), WPOO_REQUIRED_PHP, phpversion() );
		echo '</p>';
	}
	echo '<p>' . __( 'Please update the above resources to run it.', 'wpoo' ) . '</p>';
	echo '</div>';
}

/* AUTOLOADER */

function wpoo_autoload( $class_name ) {
	$domains = array(
		'WPOO'			=> WPOO_PATH . '/inc',
	);

	$parts = explode( '\\', $class_name );
	$count = count( $parts );

	$filepath = '';

	if ( $count > 1 ) {
		for ( $i = 0; $i < $count; $i++ ) {
			if ( $i == 0 ) {
				if ( isset( $domains[ $parts[ $i ] ] ) ) {
					$filepath = $domains[ $parts[ $i ] ];
					$filepath .= '/' . $parts[ $i ];
				} else {
					return false;
				}
			} elseif ( $i == $count - 1 ) {
				$filepath .= '/' . $parts[ $i ] . '.php';
			} else {
				$filepath .= '/' . $parts[ $i ];
			}
		}

		if ( file_exists( $filepath ) ) {
			require_once $filepath;
			return true;
		}
	}

	return false;
}
