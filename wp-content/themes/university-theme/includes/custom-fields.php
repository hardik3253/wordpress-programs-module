<?php
/**
 * Task 3: Custom Fields (Backend)
 * Registers post meta, React admin metabox, assets, and secure save handler.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Register Custom Post Meta with REST API support
 */
function university_register_program_post_meta() {
	register_post_meta( 'program', 'academic_year', array(
		'show_in_rest'      => true,
		'single'             => true,
		'type'               => 'string',
		'sanitize_callback'  => 'sanitize_text_field',
		'auth_callback'      => function() {
			return current_user_can( 'edit_posts' );
		},
	) );

	register_post_meta( 'program', 'duration', array(
		'show_in_rest'      => true,
		'single'             => true,
		'type'               => 'string',
		'sanitize_callback'  => 'sanitize_text_field',
		'auth_callback'      => function() {
			return current_user_can( 'edit_posts' );
		},
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
		'single'        => true,
		'type'          => 'array',
		'auth_callback' => function() {
			return current_user_can( 'edit_posts' );
		},
	) );
}
add_action( 'init', 'university_register_program_post_meta' );

/**
 * 2. Add React Metabox to 'program' Post Type
 */
function university_add_program_metabox() {
	add_meta_box(
		'university_program_details',
		__( 'Programme Details & Content Sections', 'university-theme' ),
		'university_render_program_metabox',
		'program',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'university_add_program_metabox' );

/**
 * Render container for React mount
 */
function university_render_program_metabox( $post ) {
	// Security nonce
	wp_nonce_field( 'university_save_program_meta', 'university_program_meta_nonce' );

	$academic_year    = get_post_meta( $post->ID, 'academic_year', true );
	$duration         = get_post_meta( $post->ID, 'duration', true );
	$content_sections = get_post_meta( $post->ID, 'content_sections', true );
	if ( ! is_array( $content_sections ) ) {
		$content_sections = array();
	}

	?>
	<div id="university-program-metabox-root">
		<!-- React app mounts here -->
		<div class="program-metabox-loading">
			<span class="spinner is-active" style="float:none;"></span>
			<?php esc_html_e( 'Loading Programme React Panel...', 'university-theme' ); ?>
		</div>
	</div>

	<!-- Hidden inputs synced by React for standard form POST submission -->
	<input type="hidden" id="academic_year_field" name="academic_year" value="<?php echo esc_attr( $academic_year ); ?>">
	<input type="hidden" id="duration_field" name="duration" value="<?php echo esc_attr( $duration ); ?>">
	<input type="hidden" id="content_sections_json_field" name="content_sections_json" value="<?php echo esc_attr( wp_json_encode( $content_sections ) ); ?>">
	<?php
}

/**
 * 3. Enqueue React Script & Styles for the Metabox
 */
function university_enqueue_admin_program_scripts( $hook ) {
	global $post;

	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	if ( ! $post || 'program' !== $post->post_type ) {
		return;
	}

	wp_enqueue_media();

	$build_path = get_template_directory() . '/build/index.js';
	$build_url  = get_template_directory_uri() . '/build/index.js';
	$asset_file = get_template_directory() . '/build/index.asset.php';

	$deps    = array( 'wp-element', 'wp-components', 'wp-data', 'wp-i18n' );
	$version = '1.0.0';

	if ( file_exists( $asset_file ) ) {
		$assets  = require $asset_file;
		$deps    = $assets['dependencies'];
		$version = $assets['version'];
	}

	if ( file_exists( $build_path ) ) {
		wp_enqueue_script(
			'university-program-metabox',
			$build_url,
			$deps,
			$version,
			true
		);

		$academic_year    = get_post_meta( $post->ID, 'academic_year', true );
		$duration         = get_post_meta( $post->ID, 'duration', true );
		$content_sections = get_post_meta( $post->ID, 'content_sections', true );
		if ( ! is_array( $content_sections ) ) {
			$content_sections = array();
		}

		wp_localize_script( 'university-program-metabox', 'universityProgramData', array(
			'postId'          => $post->ID,
			'academicYear'    => (string) $academic_year,
			'duration'        => (string) $duration,
			'contentSections' => $content_sections,
			'nonce'           => wp_create_nonce( 'wp_rest' ),
		) );
	}

	$css_path = get_template_directory() . '/build/style-index.css';
	$css_url  = get_template_directory_uri() . '/build/style-index.css';
	if ( file_exists( $css_path ) ) {
		wp_enqueue_style( 'university-program-metabox-style', $css_url, array(), $version );
	}
}
add_action( 'admin_enqueue_scripts', 'university_enqueue_admin_program_scripts' );

/**
 * 4. Secure Save Handler with Nonce & Capability Checks
 */
function university_save_program_meta( $post_id ) {
	// Verify nonce
	if ( ! isset( $_POST['university_program_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['university_program_meta_nonce'] ), 'university_save_program_meta' ) ) {
		return;
	}

	// Prevent autosave overrides
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check user capability
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// 1. Sanitize & save academic_year
	if ( isset( $_POST['academic_year'] ) ) {
		$sanitized_year = sanitize_text_field( wp_unslash( $_POST['academic_year'] ) );
		update_post_meta( $post_id, 'academic_year', $sanitized_year );
	}

	// 2. Sanitize & save duration
	if ( isset( $_POST['duration'] ) ) {
		$sanitized_duration = sanitize_text_field( wp_unslash( $_POST['duration'] ) );
		update_post_meta( $post_id, 'duration', $sanitized_duration );
	}

	// 3. Sanitize & save content_sections JSON
	if ( isset( $_POST['content_sections_json'] ) ) {
		$raw_json = wp_unslash( $_POST['content_sections_json'] );
		$decoded  = json_decode( $raw_json, true );

		$clean_sections = array();
		if ( is_array( $decoded ) ) {
			foreach ( $decoded as $section ) {
				if ( ! is_array( $section ) ) {
					continue;
				}
				$clean_title = isset( $section['title'] ) ? sanitize_text_field( $section['title'] ) : '';
				$clean_desc  = isset( $section['description'] ) ? sanitize_textarea_field( $section['description'] ) : '';
				$clean_image = isset( $section['image'] ) ? esc_url_raw( $section['image'] ) : '';

				if ( ! empty( $clean_title ) || ! empty( $clean_desc ) || ! empty( $clean_image ) ) {
					$clean_sections[] = array(
						'title'       => $clean_title,
						'description' => $clean_desc,
						'image'       => $clean_image,
					);
				}
			}
		}

		update_post_meta( $post_id, 'content_sections', $clean_sections );
	}
}
add_action( 'save_post_program', 'university_save_program_meta' );
