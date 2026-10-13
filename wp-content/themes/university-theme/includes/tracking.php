<?php
/**
 * Task 8: Data Tracking System
 *
 * Implements:
 * 1. Helper function program_track_attr( $type, $id, $section, $component )
 * 2. REST API endpoint: POST /wp-json/programs/v1/track
 * 3. Daily rotating log file in wp-content/uploads/logs/data-track-YYYY-MM-DD.log
 * 4. IP anonymisation (masking last octet)
 * 5. ISO 8601 timestamps and JSON Lines formatting
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Tracking HTML Attribute Helper
 *
 * @param string $type      Event or element type ('link', 'button', etc.)
 * @param string $id        Unique element identifier
 * @param string $section   Section name where interaction occurred
 * @param string $component Component type ('componentLink', 'componentButton', 'componentCard', etc.)
 * @return string Formatted data-track attribute
 */
function program_track_attr( $type, $id, $section, $component ) {
	$payload = implode( '|', array(
		sanitize_key( $type ),
		sanitize_title( $id ),
		sanitize_title( $section ),
		sanitize_text_field( $component ),
	) );

	return 'data-track="' . esc_attr( $payload ) . '"';
}

/**
 * 2. Helper to anonymise client IP address by masking the last octet
 *
 * @param string $ip
 * @return string
 */
function university_anonymize_ip( $ip ) {
	if ( empty( $ip ) ) {
		return '127.0.0.xxx';
	}

	// IPv4: mask last octet (e.g. 192.168.1.xxx)
	if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
		$parts = explode( '.', $ip );
		if ( count( $parts ) === 4 ) {
			$parts[3] = 'xxx';
			return implode( '.', $parts );
		}
	}

	// IPv6: mask last segment
	if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
		$parts = explode( ':', $ip );
		if ( count( $parts ) > 1 ) {
			$parts[ count( $parts ) - 1 ] = 'xxxx';
			return implode( ':', $parts );
		}
	}

	return 'xxx.xxx.xxx.xxx';
}

/**
 * 3. Register Tracking REST API Endpoint
 */
function university_register_tracking_endpoint() {
	register_rest_route( 'programs/v1', '/track', array(
		'methods'             => WP_REST_Server::CREATABLE, // POST
		'callback'            => 'university_handle_tracking_request',
		'permission_callback' => '__return_true', // Public tracking endpoint
		'args'                => array(
			'event_type' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'element_id' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'section' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'component' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'page_url' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'esc_url_raw',
			),
			'timestamp' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'user_agent' => array(
				'required'          => false,
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
		),
	) );
}
add_action( 'rest_api_init', 'university_register_tracking_endpoint' );

/**
 * 4. Handle incoming tracking payload and write to daily rotating log file
 */
function university_handle_tracking_request( WP_REST_Request $request ) {
	$params = $request->get_json_params();
	if ( empty( $params ) || ! is_array( $params ) ) {
		$params = $request->get_params();
	}

	if ( empty( $params ) || ! is_array( $params ) ) {
		return new WP_Error( 'invalid_payload', __( 'Malformed or empty tracking payload.', 'university-theme' ), array( 'status' => 400 ) );
	}

	// Extract client IP and anonymise
	$raw_ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
	$anonymized_ip = university_anonymize_ip( $raw_ip );

	// Build standardized record
	$record = array(
		'event_type' => ! empty( $params['event_type'] ) ? sanitize_text_field( $params['event_type'] ) : 'click',
		'element_id' => ! empty( $params['element_id'] ) ? sanitize_text_field( $params['element_id'] ) : 'unknown',
		'section'    => ! empty( $params['section'] ) ? sanitize_text_field( $params['section'] ) : 'general',
		'component'  => ! empty( $params['component'] ) ? sanitize_text_field( $params['component'] ) : 'unknown',
		'timestamp'  => ! empty( $params['timestamp'] ) ? sanitize_text_field( $params['timestamp'] ) : gmdate( 'c' ),
		'page_url'   => ! empty( $params['page_url'] ) ? esc_url_raw( $params['page_url'] ) : ( ! empty( $_SERVER['HTTP_REFERER'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : home_url( '/' ) ),
		'user_ip'    => $anonymized_ip,
		'user_agent' => ! empty( $params['user_agent'] ) ? sanitize_text_field( $params['user_agent'] ) : ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : 'Unknown' ),
	);

	// Determine log file directory: wp-content/uploads/logs/
	$upload_dir = wp_upload_dir();
	$log_dir    = trailingslashit( $upload_dir['basedir'] ) . 'logs';

	if ( ! file_exists( $log_dir ) ) {
		wp_mkdir_p( $log_dir );
		// Protect directory indexing
		$htaccess_file = $log_dir . '/.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			file_put_contents( $htaccess_file, "Options -Indexes\nDeny from all\n" );
		}
	}

	// Daily rotating log filename: data-track-YYYY-MM-DD.log
	$current_date = gmdate( 'Y-m-d' );
	$log_file     = $log_dir . '/data-track-' . $current_date . '.log';

	// Format as single JSON line (JSON Lines)
	$log_entry = wp_json_encode( $record ) . "\n";

	$written = file_put_contents( $log_file, $log_entry, FILE_APPEND | LOCK_EX );

	if ( false === $written ) {
		error_log( sprintf( '[University Tracking] Failed to write event to %s: %s', $log_file, $log_entry ) );
		return new WP_REST_Response( array(
			'success' => false,
			'message' => 'Failed to write to log file.',
		), 500 );
	}

	return new WP_REST_Response( array(
		'success'   => true,
		'timestamp' => $record['timestamp'],
		'logged'    => true,
	), 200 );
}
