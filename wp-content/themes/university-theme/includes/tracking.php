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
/**
 * 3. Register Tracking REST API Endpoint (Supports POST to track and GET to retrieve)
 */
function university_register_tracking_endpoint() {
	// POST: Receive tracking event payload
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

	// GET: Retrieve tracked log data (for Postman testing & verification)
	register_rest_route( 'programs/v1', '/track', array(
		'methods'             => WP_REST_Server::READABLE, // GET
		'callback'            => 'university_get_tracking_data',
		'permission_callback' => '__return_true',
	) );
}
add_action( 'rest_api_init', 'university_register_tracking_endpoint' );

/**
 * 4. GET Handler: Retrieve recent tracked events from the log file
 */
function university_get_tracking_data( WP_REST_Request $request ) {
	$upload_dir   = wp_upload_dir();
	$log_dir      = trailingslashit( $upload_dir['basedir'] ) . 'logs';
	$current_date = gmdate( 'Y-m-d' );
	$log_file     = $log_dir . '/data-track-' . $current_date . '.log';
	$log_file     = apply_filters( 'university_tracking_log_file', $log_file, $log_dir, $current_date );

	$limit = (int) $request->get_param( 'limit' );
	if ( $limit <= 0 ) {
		$limit = 20;
	}

	$entries = array();
	$total_lines = 0;
	if ( file_exists( $log_file ) && is_readable( $log_file ) ) {
		$lines = file( $log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
		if ( is_array( $lines ) ) {
			$total_lines = count( $lines );
			$recent_lines = array_slice( $lines, -$limit );
			foreach ( array_reverse( $recent_lines ) as $line ) {
				$decoded = json_decode( $line, true );
				if ( ! empty( $decoded ) ) {
					$entries[] = $decoded;
				}
			}
		}
	}

	return new WP_REST_Response( array(
		'success'      => true,
		'endpoint'     => 'POST /wp-json/programs/v1/track',
		'log_file'     => basename( $log_file ),
		'total_events' => $total_lines,
		'returned'     => count( $entries ),
		'entries'      => $entries,
		'postman_usage' => array(
			'method'  => 'POST',
			'headers' => array( 'Content-Type' => 'application/json' ),
			'sample_body' => array(
				'type'         => 'click',
				'event'        => 'link',
				'link'         => array( 'label' => 'apply-now' ),
				'content_data' => array(
					'sectionName' => 'programme-overview',
					'uiComponent' => 'componentLink',
				),
			),
		),
	), 200 );
}

/**
 * 5. POST Handler: Handle incoming tracking payload and write to daily rotating log file
 */
function university_handle_tracking_request( WP_REST_Request $request ) {
	// Parse JSON params from request body
	$params = $request->get_json_params();

	// Fallback 1: Parse raw body in case Content-Type header was omitted in Postman
	if ( empty( $params ) || ! is_array( $params ) ) {
		$raw_body = $request->get_body();
		if ( ! empty( $raw_body ) ) {
			$decoded = json_decode( $raw_body, true );
			if ( is_array( $decoded ) ) {
				$params = $decoded;
			}
		}
	}

	// Fallback 2: Parse form or URL parameters
	if ( empty( $params ) || ! is_array( $params ) ) {
		$params = $request->get_params();
	}

	if ( empty( $params ) || ! is_array( $params ) ) {
		return new WP_Error(
			'invalid_payload',
			__( 'Malformed or empty tracking payload. Please send a JSON object with type, event/element_id, section, and component.', 'university-theme' ),
			array(
				'status'      => 400,
				'sample_body' => array(
					'type'         => 'click',
					'event'        => 'link',
					'link'         => array( 'label' => 'apply-now' ),
					'content_data' => array(
						'sectionName' => 'programme-overview',
						'uiComponent' => 'componentLink',
					),
				),
			)
		);
	}

	// Extract client IP and anonymise
	$raw_ip = ! empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
	$anonymized_ip = university_anonymize_ip( $raw_ip );

	// Resolve event type, element_id, section, and component from either flat or nested spec format
	$event_type = ! empty( $params['event_type'] ) ? sanitize_text_field( $params['event_type'] ) : ( ! empty( $params['type'] ) ? sanitize_text_field( $params['type'] ) : 'click' );

	$element_id = 'unknown';
	if ( ! empty( $params['element_id'] ) ) {
		$element_id = sanitize_text_field( $params['element_id'] );
	} elseif ( ! empty( $params['link']['label'] ) ) {
		$element_id = sanitize_text_field( $params['link']['label'] );
	} elseif ( ! empty( $params['button']['label'] ) ) {
		$element_id = sanitize_text_field( $params['button']['label'] );
	}

	$section = 'general';
	if ( ! empty( $params['section'] ) ) {
		$section = sanitize_text_field( $params['section'] );
	} elseif ( ! empty( $params['content_data']['sectionName'] ) ) {
		$section = sanitize_text_field( $params['content_data']['sectionName'] );
	}

	$component = 'unknown';
	if ( ! empty( $params['component'] ) ) {
		$component = sanitize_text_field( $params['component'] );
	} elseif ( ! empty( $params['content_data']['uiComponent'] ) ) {
		$component = sanitize_text_field( $params['content_data']['uiComponent'] );
	}

	// Build standardized record
	$record = array(
		'event_type' => $event_type,
		'element_id' => $element_id,
		'section'    => $section,
		'component'  => $component,
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
	$log_file     = apply_filters( 'university_tracking_log_file', $log_file, $log_dir, $current_date );

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
		'record'    => $record,
	), 200 );
}
