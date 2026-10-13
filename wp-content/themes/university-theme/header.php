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
		<!-- Main Row: Logo on Left, Navigation + Apply Now on Right -->
		<div class="header-main-row">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-logo" <?php echo program_track_attr( 'link', 'header-logo', 'site-header', 'componentLink' ); ?>>
				<div class="logo-crest">
					<svg width="44" height="52" viewBox="0 0 44 52" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<!-- 1495 Year Inscription -->
						<text x="1" y="8.5" font-family="'Cinzel', Georgia, serif" font-size="8.5" font-weight="700" fill="#FFFFFF" letter-spacing="0.5">1495</text>
						<!-- Aberdeen Shield Outer Border -->
						<path d="M4 13H40V31C40 40 22 48.5 22 48.5C22 48.5 4 40 4 31V13Z" fill="#0C2340" stroke="#FFFFFF" stroke-width="1.6"/>
						<!-- Quarter Division Lines -->
						<line x1="22" y1="13" x2="22" y2="48" stroke="#F4B41A" stroke-width="1.2"/>
						<line x1="4" y1="28" x2="40" y2="28" stroke="#F4B41A" stroke-width="1.2"/>
						<!-- Q1: Red Quarter + Gold Mitre -->
						<path d="M5 14H21V27H5V14Z" fill="#B31B2C"/>
						<path d="M13 16L9.5 20V24.5H16.5V20L13 16Z" fill="#F4B41A" stroke="#FFFFFF" stroke-width="0.5"/>
						<circle cx="13" cy="18.5" r="1.2" fill="#FFFFFF"/>
						<!-- Q2: Dark Blue Quarter + White Open Book -->
						<path d="M23 14H39V27H23V14Z" fill="#0C2340"/>
						<path d="M26 18C28 17.5 30.5 17.5 31 23C31.5 17.5 34 17.5 36 18V24C34 23.5 31.5 23.5 31 25.5C30.5 23.5 28 23.5 26 24V18Z" fill="#FFFFFF" stroke="#F4B41A" stroke-width="0.4"/>
						<!-- Q3: Dark Blue Quarter + Tower/Castle -->
						<path d="M5 29H21V41C17 39 10 35 5 29Z" fill="#0C2340"/>
						<rect x="9.5" y="32" width="7" height="6" fill="#FFFFFF"/>
						<rect x="8.5" y="30.5" width="9" height="1.5" fill="#F4B41A"/>
						<!-- Q4: Red Quarter + Seal -->
						<path d="M23 29H39C34 35 27 39 23 41V29Z" fill="#B31B2C"/>
						<circle cx="31" cy="34.5" r="2.8" fill="#F4B41A" stroke="#FFFFFF" stroke-width="0.5"/>
					</svg>
				</div>
				<div class="logo-text">
					<span class="logo-title">UNIVERSITY OF</span>
					<span class="logo-highlight">ABERDEEN</span>
					<span class="logo-sub">MUMBAI</span>
				</div>
			</a>

			<!-- Navigation Links & Apply Now Button -->
			<div class="header-right-group">
				<nav class="main-navigation" id="main-navigation" aria-label="Main Navigation">
					<ul class="nav-menu">
						<li><a href="<?php echo esc_url( home_url( '/#study' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-study', 'main-navigation', 'componentLink' ); ?>>Study</a></li>
						<li><a href="<?php echo esc_url( home_url( '/#admission' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-admission', 'main-navigation', 'componentLink' ); ?>>Admission</a></li>
						<li><a href="<?php echo esc_url( home_url( '/#about-us' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-about-us', 'main-navigation', 'componentLink' ); ?>>About Us</a></li>
						<li><a href="<?php echo esc_url( home_url( '/#alumni' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-alumni', 'main-navigation', 'componentLink' ); ?>>Alumni</a></li>
						<li><a href="<?php echo esc_url( home_url( '/#contact-us' ) ); ?>" <?php echo program_track_attr( 'link', 'nav-contact-us', 'main-navigation', 'componentLink' ); ?>>Contact Us</a></li>
					</ul>
				</nav>

				<button type="button" class="btn btn-gold btn-header-apply js-open-lead-modal" <?php echo program_track_attr( 'button', 'header-apply-now', 'site-header', 'componentButton' ); ?>>
					Apply Now
				</button>

				<!-- Mobile Menu Toggle -->
				<button type="button" class="mobile-menu-toggle" aria-label="Toggle navigation" id="mobile-menu-toggle">
					<span></span>
					<span></span>
					<span></span>
				</button>
			</div>
		</div>

		<!-- Sub Row on Right: University of Aberdeen UK with yellow dotted underline -->
		<div class="header-sub-row">
			<a href="https://www.abdn.ac.uk" target="_blank" rel="noopener noreferrer" class="link-uk-portal" <?php echo program_track_attr( 'link', 'header-uk-portal', 'site-header', 'componentLink' ); ?>>
				University of Aberdeen UK
			</a>
		</div>
	</div>
</header>
<main id="primary-content" class="site-main">
