<?php
/**
 * Task 4: Frontend Routing & Rewrite Rules
 * URL Hierarchy:
 * - /programmes               -> All programmes listing
 * - /programmes/undergraduate -> Undergraduate programmes
 * - /programmes/postgraduate  -> Postgraduate programmes
 * - /programmes/{slug}        -> Single programme
 *
 * Ensures no collision between taxonomy terms and single programme slugs.
 * Manages rewrite rules, query vars, and flush on theme activation.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register query vars for program type filter
 */
function university_register_query_vars( $vars ) {
	$vars[] = 'program_type_filter';
	return $vars;
}
add_filter( 'query_vars', 'university_register_query_vars' );

/**
 * Register custom rewrite rules with strict precedence to avoid slug collision
 */
function university_register_rewrite_rules() {
	// 1. Strict rule for known taxonomy terms (undergraduate | postgraduate)
	add_rewrite_rule(
		'^programmes/(undergraduate|postgraduate)/?$',
		'index.php?post_type=program&program-type=$matches[1]&program_type_filter=$matches[1]',
		'top'
	);

	// 2. Listing archive
	add_rewrite_rule(
		'^programmes/?$',
		'index.php?post_type=program',
		'top'
	);

	// 3. Single program detail (matches any slug that is not a known filter term)
	add_rewrite_rule(
		'^programmes/([^/]+)/?$',
		'index.php?program=$matches[1]',
		'bottom'
	);
}
add_action( 'init', 'university_register_rewrite_rules', 10 );

/**
 * Flush rewrite rules only on theme activation/switch, NEVER on every request
 */
function university_flush_rewrites_on_switch() {
	university_register_cpt_and_taxonomy();
	university_register_rewrite_rules();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'university_flush_rewrites_on_switch' );

/**
 * Breadcrumb Helper Function
 * Returns structured array of breadcrumb items: array( array( 'label' => '', 'url' => '' ) )
 */
function university_get_breadcrumbs() {
	$crumbs = array(
		array(
			'label' => __( 'Home', 'university-theme' ),
			'url'   => home_url( '/' ),
			'track' => 'breadcrumb-home',
		),
	);

	if ( is_singular( 'program' ) ) {
		$crumbs[] = array(
			'label' => __( 'Programmes', 'university-theme' ),
			'url'   => home_url( '/programmes' ),
			'track' => 'breadcrumb-programmes',
		);

		$terms = get_the_terms( get_the_ID(), 'program-type' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$term = $terms[0];
			$crumbs[] = array(
				'label' => $term->name,
				'url'   => home_url( '/programmes/' . $term->slug ),
				'track' => 'breadcrumb-' . sanitize_title( $term->slug ),
			);
		}

		$crumbs[] = array(
			'label' => get_the_title(),
			'url'   => '',
			'track' => '',
		);
	} elseif ( is_post_type_archive( 'program' ) || get_query_var( 'program_type_filter' ) ) {
		$crumbs[] = array(
			'label' => __( 'Programmes', 'university-theme' ),
			'url'   => ( get_query_var( 'program_type_filter' ) ) ? home_url( '/programmes' ) : '',
			'track' => 'breadcrumb-programmes',
		);

		$filter = get_query_var( 'program_type_filter' );
		if ( ! empty( $filter ) ) {
			$term = get_term_by( 'slug', $filter, 'program-type' );
			$crumbs[] = array(
				'label' => $term ? $term->name : ucfirst( $filter ),
				'url'   => '',
				'track' => '',
			);
		}
	}

	return $crumbs;
}
