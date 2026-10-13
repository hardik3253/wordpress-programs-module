<?php
/**
 * Plugin Name: Programs Module
 * Plugin URI: https://example.com/programs-module
 * Description: Custom Post Type, taxonomy, metadata, routing, Interactivity API, and click tracking for University Programmes.
 * Version: 1.0.0
 * Author: Candidate
 * Text Domain: programs-module
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PROGRAMS_MODULE_PATH', plugin_dir_path( __FILE__ ) );
define( 'PROGRAMS_MODULE_URL', plugin_dir_url( __FILE__ ) );

/**
 * Task 2: Content Structure - Register CPT & Taxonomy
 */
function programs_register_cpt_and_taxonomy() {
	// Register CPT 'program'
	$labels = array(
		'name'               => _x( 'Programs', 'post type general name', 'programs-module' ),
		'singular_name'      => _x( 'Program', 'post type singular name', 'programs-module' ),
		'menu_name'          => _x( 'Programs', 'admin menu', 'programs-module' ),
		'add_new'            => _x( 'Add New', 'program', 'programs-module' ),
		'add_new_item'       => __( 'Add New Program', 'programs-module' ),
		'edit_item'          => __( 'Edit Program', 'programs-module' ),
		'new_item'           => __( 'New Program', 'programs-module' ),
		'view_item'          => __( 'View Program', 'programs-module' ),
		'search_items'       => __( 'Search Programs', 'programs-module' ),
		'not_found'          => __( 'No programs found', 'programs-module' ),
		'not_found_in_trash' => __( 'No programs found in Trash', 'programs-module' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'programmes', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'programmes',
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => true,
	);
	register_post_type( 'program', $args );

	// Register Hierarchical Taxonomy 'program-type'
	$tax_labels = array(
		'name'              => _x( 'Program Types', 'taxonomy general name', 'programs-module' ),
		'singular_name'     => _x( 'Program Type', 'taxonomy singular name', 'programs-module' ),
		'search_items'      => __( 'Search Program Types', 'programs-module' ),
		'all_items'         => __( 'All Program Types', 'programs-module' ),
		'parent_item'       => __( 'Parent Program Type', 'programs-module' ),
		'parent_item_colon' => __( 'Parent Program Type:', 'programs-module' ),
		'edit_item'         => __( 'Edit Program Type', 'programs-module' ),
		'update_item'       => __( 'Update Program Type', 'programs-module' ),
		'add_new_item'      => __( 'Add New Program Type', 'programs-module' ),
		'new_item_name'     => __( 'New Program Type Name', 'programs-module' ),
		'menu_name'         => __( 'Program Types', 'programs-module' ),
	);

	$tax_args = array(
		'hierarchical'      => true,
		'labels'            => $tax_labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'program-type', 'with_front' => false ),
		'show_in_rest'      => true,
	);
	register_taxonomy( 'program-type', array( 'program' ), $tax_args );
}
add_action( 'init', 'programs_register_cpt_and_taxonomy', 0 );

/**
 * Task 3: Register Custom Post Meta
 */
function programs_register_post_meta() {
	register_post_meta( 'program', 'academic_year', array(
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );

	register_post_meta( 'program', 'duration', array(
		'show_in_rest' => true,
		'single'       => true,
		'type'         => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );

	register_post_meta( 'program', 'content_sections', array(
		'show_in_rest' => array(
			'schema' => array(
				'type'  => 'array',
				'items' => array(
					'type'       => 'object',
					'properties' => array(
						'title'       => array( 'type' => 'string' ),
						'description' => array( 'type' => 'string' ),
						'image'       => array( 'type' => 'string' ),
					),
				),
			),
		),
		'single'       => true,
		'type'         => 'array',
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
	) );
}
add_action( 'init', 'programs_register_post_meta' );

/**
 * Task 4: Frontend Routing Rewrites
 */
function programs_custom_rewrite_rules() {
	add_rewrite_tag( '%program_type_filter%', '([^&]+)' );
	
	// /programmes/undergraduate and /programmes/postgraduate
	add_rewrite_rule(
		'^programmes/(undergraduate|postgraduate)/?$',
		'index.php?post_type=program&program_type_filter=$matches[1]',
		'top'
	);
	
	// /programmes/{program-slug}
	add_rewrite_rule(
		'^programmes/([^/]+)/?$',
		'index.php?post_type=program&name=$matches[1]',
		'top'
	);
}
add_action( 'init', 'programs_custom_rewrite_rules' );

function programs_query_vars( $vars ) {
	$vars[] = 'program_type_filter';
	return $vars;
}
add_filter( 'query_vars', 'programs_query_vars' );

/**
 * Task 8.2 & 8.3: Reusable PHP Tracking Helper
 */
if ( ! function_exists( 'program_track_attr' ) ) {
	function program_track_attr( $event, $label, $section, $component ) {
		$event_type = ( 'button' === $event ) ? 'button' : 'link';
		$payload = array(
			'type'         => 'click',
			'event'        => $event_type,
			$event_type    => array(
				'label' => sanitize_title( $label ),
			),
			'content_data' => array(
				'sectionName' => sanitize_text_field( $section ),
				'uiComponent' => sanitize_text_field( $component ),
			),
		);
		return 'data-track="' . esc_attr( wp_json_encode( $payload ) ) . '"';
	}
}

/**
 * Task 8: Click Tracking REST API Endpoint & Log Writer
 */
function programs_register_tracking_rest_route() {
	register_rest_route( 'programs/v1', '/track', array(
		'methods'             => 'POST',
		'callback'            => 'programs_handle_tracking_request',
		'permission_callback' => '__return_true', // Public endpoint with strict validation & size limiter
	) );
}
add_action( 'rest_api_init', 'programs_register_tracking_rest_route' );

function programs_handle_tracking_request( WP_REST_Request $request ) {
	$body = $request->get_body();
	if ( empty( $body ) || strlen( $body ) > 4096 ) {
		return new WP_Error( 'invalid_payload', 'Payload exceeds size limit or is empty', array( 'status' => 400 ) );
	}

	$data = json_decode( $body, true );
	if ( ! is_array( $data ) || ( isset( $data['type'] ) && 'click' !== $data['type'] ) ) {
		return new WP_Error( 'invalid_data', 'Type must be click', array( 'status' => 400 ) );
	}

	$event = isset( $data['event'] ) ? sanitize_text_field( $data['event'] ) : '';
	if ( ! in_array( $event, array( 'link', 'button' ), true ) ) {
		return new WP_Error( 'invalid_event', 'Event must be link or button', array( 'status' => 400 ) );
	}

	$label_raw = isset( $data[ $event ]['label'] ) ? $data[ $event ]['label'] : '';
	$label = sanitize_title( $label_raw );

	$section_raw   = isset( $data['content_data']['sectionName'] ) ? $data['content_data']['sectionName'] : '';
	$component_raw = isset( $data['content_data']['uiComponent'] ) ? $data['content_data']['uiComponent'] : '';

	$sanitized_payload = array(
		'type'         => 'click',
		'event'        => $event,
		$event         => array( 'label' => $label ),
		'content_data' => array(
			'sectionName' => sanitize_text_field( $section_raw ),
			'uiComponent' => sanitize_text_field( $component_raw ),
		),
	);

	$page_url = $request->get_header( 'referer' );
	if ( $page_url ) {
		$parsed = wp_parse_url( $page_url );
		$page_url = isset( $parsed['path'] ) ? $parsed['path'] : '/';
	} else {
		$page_url = '/';
	}

	$log_entry = array(
		'timestamp' => gmdate( 'c' ),
		'page_url'  => sanitize_text_field( $page_url ),
		'payload'   => $sanitized_payload,
	);

	// Log directory in wp-content/uploads/logs/
	$upload_dir = wp_upload_dir();
	$log_dir    = $upload_dir['basedir'] . '/logs';
	if ( ! file_exists( $log_dir ) ) {
		wp_mkdir_p( $log_dir );
		// Add security protect files
		file_put_contents( $log_dir . '/index.php', '<?php // Silence is golden' );
		file_put_contents( $log_dir . '/.htaccess', 'Deny from all' );
	}

	$date_suffix = gmdate( 'Y-m-d' );
	$log_file    = $log_dir . '/data-track-' . $date_suffix . '.log';
	$log_file    = apply_filters( 'programs_tracking_log_path', $log_file );

	$json_line = wp_json_encode( $log_entry ) . "\n";
	$written   = file_put_contents( $log_file, $json_line, FILE_APPEND | LOCK_EX );

	if ( false === $written ) {
		return new WP_REST_Response( array( 'status' => 'error', 'message' => 'Failed to write log' ), 500 );
	}

	return new WP_REST_Response( array( 'status' => 'success' ), 200 );
}

/**
 * Seeder function for sample data (Task 2: at least 10 programs, 5 each)
 */
function programs_seed_initial_data() {
	if ( get_option( 'programs_data_seeded' ) ) {
		return;
	}

	// Create terms
	$terms = array(
		'Undergraduate' => 'undergraduate',
		'Postgraduate'  => 'postgraduate',
	);

	$term_ids = array();
	foreach ( $terms as $name => $slug ) {
		$term = get_term_by( 'slug', $slug, 'program-type' );
		if ( ! $term ) {
			$created = wp_insert_term( $name, 'program-type', array( 'slug' => $slug ) );
			if ( ! is_wp_error( $created ) ) {
				$term_ids[ $slug ] = $created['term_id'];
			}
		} else {
			$term_ids[ $slug ] = $term->term_id;
		}
	}

	// 10 Sample programs
	$sample_programs = array(
		// 5 Undergraduate
		array(
			'title'       => 'Bachelor of Computer Applications (BCA)',
			'slug'        => 'bachelor-of-computer-applications-bca',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'content'     => 'Learn programming, software development, database architecture, and modern cloud technologies through practical, industry-focused education.',
			'sections'    => array(
				array( 'title' => 'Curriculum Overview', 'description' => 'Comprehensive core modules covering Full-Stack Development, Data Structures, and Cloud Computing.' ),
				array( 'title' => 'Industry Placement', 'description' => 'Guaranteed internship semester with leading global tech companies in Mumbai and worldwide.' ),
			),
		),
		array(
			'title'       => 'Bachelor of Engineering (B.E.) in Computer Engineering',
			'slug'        => 'bachelor-of-engineering-be',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '4 Years',
			'content'     => 'Gain technical knowledge and practical engineering skills to solve real-world problems with advanced robotics, embedded systems, and computing systems.',
			'sections'    => array(
				array( 'title' => 'Hands-on Labs', 'description' => 'State-of-the-art electronics, IoT, and high-performance computing laboratories.' ),
				array( 'title' => 'Accreditation', 'description' => 'Globally recognized engineering credentials aligned with British and Indian standards.' ),
			),
		),
		array(
			'title'       => 'B.Sc. (Hons) in Artificial Intelligence & Data Science',
			'slug'        => 'bsc-artificial-intelligence-data-science',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'content'     => 'Master modern machine learning algorithms, deep neural architectures, and big data pipelines guided by world-class academic researchers.',
			'sections'    => array(
				array( 'title' => 'Key Modules', 'description' => 'Deep Learning, Predictive Modeling, Natural Language Processing, and Ethical AI Systems.' ),
			),
		),
		array(
			'title'       => 'Bachelor of Business Administration (BBA) - Global Business',
			'slug'        => 'bachelor-of-business-administration-bba',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'content'     => 'Develop strategic leadership, international finance, and entrepreneurship capabilities designed for tomorrow\'s corporate executives.',
			'sections'    => array(
				array( 'title' => 'Global Exchange', 'description' => 'Opportunity to spend one semester at the Aberdeen campus in Scotland.' ),
			),
		),
		array(
			'title'       => 'B.Sc. (Hons) in Cyber Security & Digital Forensics',
			'slug'        => 'bsc-cyber-security-digital-forensics',
			'type'        => 'undergraduate',
			'year'        => '2025-26',
			'duration'    => '3 Years',
			'content'     => 'Protect mission-critical networks, defend infrastructure against cyber vulnerabilities, and master modern incident response analysis.',
			'sections'    => array(
				array( 'title' => 'Defense Simulation', 'description' => 'Red-team vs Blue-team live cyber range scenarios and penetration testing labs.' ),
			),
		),

		// 5 Postgraduate
		array(
			'title'       => 'Master of Computer Applications (MCA)',
			'slug'        => 'master-of-computer-applications-mca',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'content'     => 'Build advanced skills in software architecture, distributed systems, container orchestration, and emerging enterprise engineering paradigms.',
			'sections'    => array(
				array( 'title' => 'Advanced Specialization', 'description' => 'Deep dive into microservices, DevOps pipelines, and enterprise distributed systems.' ),
			),
		),
		array(
			'title'       => 'Master of Engineering (M.E.) in Software Systems',
			'slug'        => 'master-of-engineering-me',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'content'     => 'Advance your technical expertise through specialized research, high-reliability engineering, and industrial collaboration.',
			'sections'    => array(
				array( 'title' => 'Research Thesis', 'description' => 'Supervised capstone dissertation addressing open industrial challenges.' ),
			),
		),
		array(
			'title'       => 'M.Sc. in Data Science & Business Analytics',
			'slug'        => 'msc-data-science-business-analytics',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'content'     => 'Harness big data and quantitative modeling to extract high-value insights, drive algorithmic business decisions, and innovate.',
			'sections'    => array(
				array( 'title' => 'Applied Machine Learning', 'description' => 'Practical modeling with PyTorch, TensorFlow, and enterprise cloud data lakes.' ),
			),
		),
		array(
			'title'       => 'MBA - International Business & Digital Transformation',
			'slug'        => 'mba-international-business',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'content'     => 'Executive management education providing cross-cultural leadership skills, financial acumen, and tech-driven corporate strategy.',
			'sections'    => array(
				array( 'title' => 'Executive Mentorship', 'description' => 'Direct coaching from Fortune 500 C-level alumni and Scottish business leaders.' ),
			),
		),
		array(
			'title'       => 'M.Sc. in Cloud Computing & DevOps Architecture',
			'slug'        => 'msc-cloud-computing-devops',
			'type'        => 'postgraduate',
			'year'        => '2025-26',
			'duration'    => '2 Years',
			'content'     => 'Design scalable serverless architectures, multicloud infrastructures, and automated Continuous Integration / Continuous Deployment pipelines.',
			'sections'    => array(
				array( 'title' => 'Cloud Certification', 'description' => 'Curriculum aligned with AWS Certified Solutions Architect and Azure DevOps certifications.' ),
			),
		),
	);

	foreach ( $sample_programs as $p ) {
		$existing = get_page_by_path( $p['slug'], OBJECT, 'program' );
		if ( ! $existing ) {
			$post_id = wp_insert_post( array(
				'post_title'   => $p['title'],
				'post_name'    => $p['slug'],
				'post_content' => $p['content'],
				'post_status'  => 'publish',
				'post_type'    => 'program',
			) );

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, 'academic_year', $p['year'] );
				update_post_meta( $post_id, 'duration', $p['duration'] );
				update_post_meta( $post_id, 'content_sections', $p['sections'] );

				if ( isset( $term_ids[ $p['type'] ] ) ) {
					wp_set_object_terms( $post_id, array( (int) $term_ids[ $p['type'] ] ), 'program-type' );
				}
			}
		}
	}

	update_option( 'programs_data_seeded', 1 );
}
register_activation_hook( __FILE__, function() {
	programs_register_cpt_and_taxonomy();
	programs_custom_rewrite_rules();
	flush_rewrite_rules();
	programs_seed_initial_data();
} );

// Also seed on admin_init if not yet seeded
add_action( 'admin_init', 'programs_seed_initial_data' );
