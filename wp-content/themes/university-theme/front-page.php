<?php
/**
 * Front Page Template - Matching Figma Desktop P1
 * Supports dynamic Gutenberg blocks manageable from the WordPress admin side.
 */
get_header();

// Check if current front page has Gutenberg block content
$has_blocks = false;
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$content = get_the_content();
		if ( ! empty( trim( $content ) ) && has_blocks( $content ) ) {
			the_content();
			$has_blocks = true;
		}
	}
}

// Fallback to rendering the 3 default Aberdeen blocks if the page has not been customized with blocks yet
if ( ! $has_blocks ) {
	echo aberdeen_render_hero_slider_block( array() );
	echo aberdeen_render_careers_block( array() );
	echo aberdeen_render_our_programmes_block( array() );
}

get_footer();
