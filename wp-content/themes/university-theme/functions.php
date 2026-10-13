<?php
/**
 * University Theme Functions and Definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function university_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'university-theme' ),
		'footer'  => __( 'Footer Navigation', 'university-theme' ),
	) );
}
add_action( 'after_setup_theme', 'university_theme_setup' );

// Load Gutenberg custom blocks
require_once get_template_directory() . '/includes/blocks.php';


function university_theme_scripts() {
	// Google Fonts: Inter & Outfit
	wp_enqueue_style( 'university-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap', array(), null );

	// Theme Stylesheet
	wp_enqueue_style( 'university-theme-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0' );

	// Main JS Script
	wp_enqueue_script( 'university-theme-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true );

	// Pass tracking REST endpoint and nonce
	wp_localize_script( 'university-theme-main', 'AberdeenConfig', array(
		'restUrl'     => esc_url_raw( rest_url( 'programs/v1/track' ) ),
		'programsUrl' => esc_url_raw( home_url( '/programmes' ) ),
		'siteUrl'     => esc_url_raw( home_url( '/' ) ),
	) );
}
add_action( 'wp_enqueue_scripts', 'university_theme_scripts' );

/**
 * Custom template routing for /programmes, /programmes/undergraduate, /programmes/postgraduate
 */
function university_theme_template_include( $template ) {
	if ( is_singular( 'program' ) ) {
		$single_template = locate_template( array( 'single-program.php' ) );
		if ( $single_template ) {
			return $single_template;
		}
	}

	$filter = get_query_var( 'program_type_filter' );
	if ( ! empty( $filter ) || is_post_type_archive( 'program' ) ) {
		$archive_template = locate_template( array( 'archive-program.php', 'template-programmes.php' ) );
		if ( $archive_template ) {
			return $archive_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'university_theme_template_include' );

/**
 * Helper to fetch programs with meta
 */
function university_get_programs( $term_slug = '' ) {
	$args = array(
		'post_type'      => 'program',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	);

	if ( ! empty( $term_slug ) && 'all' !== $term_slug ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'program-type',
				'field'    => 'slug',
				'terms'    => sanitize_title( $term_slug ),
			),
		);
	}

	return get_posts( $args );
}
