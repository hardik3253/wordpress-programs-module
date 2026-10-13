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
				<?php
				$logo_url = get_template_directory_uri() . '/assets/images/logo.png';
				if ( file_exists( WP_CONTENT_DIR . '/uploads/2026/10/uam-logo.png' ) ) {
					$logo_url = content_url( '/uploads/2026/10/uam-logo.png' );
				}
				?>
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="University of Aberdeen Mumbai" class="site-logo-image" width="180" height="74" />
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
