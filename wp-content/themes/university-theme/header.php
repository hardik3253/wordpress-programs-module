<?php
/**
 * Header Template matching University of Aberdeen Figma Design
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="site-header">
	<div class="header-container">
		<!-- Brand Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo" <?php echo program_track_attr( 'link', 'header-logo', 'site-header', 'componentLink' ); ?>>
			<div class="logo-crest">
				<svg width="34" height="42" viewBox="0 0 34 42" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M17 2L3 7V21C3 29.5 9 37.5 17 40C25 37.5 31 29.5 31 21V7L17 2Z" fill="#F4B41A" stroke="#FFFFFF" stroke-width="2"/>
					<path d="M17 8L10 13V22C10 26.5 13 30.5 17 32C21 30.5 24 26.5 24 22V13L17 8Z" fill="#08182B"/>
					<circle cx="17" cy="18" r="3.5" fill="#F4B41A"/>
				</svg>
			</div>
			<div class="logo-text">
				<span class="logo-title">UNIVERSITY OF</span>
				<span class="logo-highlight">ABERDEEN</span>
				<span class="logo-sub">MUMBAI</span>
			</div>
		</a>

		<!-- Navigation Links -->
		<nav class="main-navigation" id="main-navigation" aria-label="Main Navigation">
			<ul class="nav-menu">
				<li><a href="<?php echo esc_url( home_url( '/#study' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-study', 'main-navigation', 'componentLink' ); ?>>Study</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#admission' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-admission', 'main-navigation', 'componentLink' ); ?>>Admission</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#about-us' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-about-us', 'main-navigation', 'componentLink' ); ?>>About Us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#alumni' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-alumni', 'main-navigation', 'componentLink' ); ?>>Alumni</a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contact-us' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-contact-us', 'main-navigation', 'componentLink' ); ?>>Contact Us</a></li>
			</ul>
		</nav>

		<!-- Header CTA Buttons -->
		<div class="header-cta-group">
			<button type="button" class="btn btn-gold btn-header-apply js-open-lead-modal" <?php echo program_track_attr( 'button', 'header-apply-now', 'site-header', 'componentButton' ); ?>>
				Apply Now
			</button>
			<a href="https://www.abdn.ac.uk" target="_blank" rel="noopener noreferrer" class="link-uk-portal" <?php echo program_track_attr( 'link', 'header-uk-portal', 'site-header', 'componentLink' ); ?>>
				University of Aberdeen UK <span class="arrow">&rarr;</span>
			</a>
		</div>

		<!-- Mobile Menu Toggle -->
		<button type="button" class="mobile-menu-toggle" aria-label="Toggle navigation" id="mobile-menu-toggle">
			<span></span>
			<span></span>
			<span></span>
		</button>
	</div>
</header>
<main id="primary-content" class="site-main">
