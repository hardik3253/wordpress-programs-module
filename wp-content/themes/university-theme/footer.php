<?php
/**
 * Footer Template matching University of Aberdeen Figma Design
 */
?>
</main><!-- #primary-content -->

<footer class="site-footer" id="site-footer">
	<div class="footer-container">
		<!-- Left Column: University info -->
		<div class="footer-brand-column">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" <?php echo program_track_attr( 'link', 'footer-logo', 'site-footer', 'componentLink' ); ?>>
				<?php
				$footer_logo_url = get_template_directory_uri() . '/assets/images/logo.png';
				if ( file_exists( WP_CONTENT_DIR . '/uploads/2026/10/uam-logo.png' ) ) {
					$footer_logo_url = content_url( '/uploads/2026/10/uam-logo.png' );
				}
				?>
				<img src="<?php echo esc_url( $footer_logo_url ); ?>" alt="University of Aberdeen Mumbai" class="footer-logo-image" width="160" height="66" />
			</a>

			<address class="footer-address">
				The University of Aberdeen<br>
				India Location,<br>
				Mumbai<br>
				<span class="footer-tel">Tel: <a href="tel:+911231231212" <?php echo program_track_attr( 'link', 'footer-phone', 'site-footer', 'componentLink' ); ?>>+91 1231231212</a></span>
			</address>

			<ul class="footer-quick-links">
				<li><a href="<?php echo esc_url( home_url( '/#contact-us' ) ); ?>" <?php echo program_track_attr( 'link', 'footer-contact', 'site-footer', 'componentLink' ); ?>>Contact Us</a></li>
				<li><a href="<?php echo esc_url( home_url( '/student-policies' ) ); ?>" <?php echo program_track_attr( 'link', 'footer-student-policies', 'site-footer', 'componentLink' ); ?>>Student Policies</a></li>
			</ul>
		</div>

		<!-- Right Column: Connect With Us -->
		<div class="footer-connect-column">
			<h4 class="connect-title">Connect With Us</h4>
			<div class="social-links">
				<a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)" class="social-icon" <?php echo program_track_attr( 'link', 'footer-social-x', 'site-footer', 'componentLink' ); ?>>
					<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
						<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
					</svg>
				</a>
				<a href="https://instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="social-icon" <?php echo program_track_attr( 'link', 'footer-social-instagram', 'site-footer', 'componentLink' ); ?>>
					<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
						<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
					</svg>
				</a>
				<a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="social-icon" <?php echo program_track_attr( 'link', 'footer-social-linkedin', 'site-footer', 'componentLink' ); ?>>
					<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
						<path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
					</svg>
				</a>
			</div>
		</div>
	</div>

	<!-- Sub-footer copyright bar -->
	<div class="footer-bottom-bar">
		<div class="footer-bottom-container">
			<ul class="footer-legal-links">
				<li><a href="<?php echo esc_url( home_url( '/privacy' ) ); ?>" <?php echo program_track_attr( 'link', 'footer-privacy', 'footer-bottom', 'componentLink' ); ?>>Privacy</a></li>
				<li><span class="sep">|</span></li>
				<li><a href="<?php echo esc_url( home_url( '/accessibility' ) ); ?>" <?php echo program_track_attr( 'link', 'footer-accessibility', 'footer-bottom', 'componentLink' ); ?>>Accessibility</a></li>
				<li><span class="sep">|</span></li>
				<li><a href="<?php echo esc_url( home_url( '/cookies' ) ); ?>" <?php echo program_track_attr( 'link', 'footer-cookies', 'footer-bottom', 'componentLink' ); ?>>Cookies</a></li>
			</ul>
			<p class="copyright-text">&copy; <?php echo esc_html( date( 'Y' ) ); ?> University of Aberdeen</p>
		</div>
	</div>
</footer>

<?php
// Render the Lead Form Popup & Thank You Modal
get_template_part( 'template-parts/lead-modal' );
?>

<?php wp_footer(); ?>
</body>
</html>
