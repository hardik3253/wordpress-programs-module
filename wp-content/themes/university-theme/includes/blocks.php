<?php
/**
 * Custom Gutenberg Blocks for University of Aberdeen Mumbai Theme
 * Implemented natively using core WordPress block APIs (no third-party plugins).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom block category
 */
function aberdeen_register_block_categories( $categories ) {
	return array_merge(
		array(
			array(
				'slug'  => 'aberdeen-blocks',
				'title' => __( 'Aberdeen Theme Blocks', 'university-theme' ),
				'icon'  => 'shield',
			),
		),
		$categories
	);
}
add_filter( 'block_categories_all', 'aberdeen_register_block_categories', 10, 1 );

/**
 * Enqueue editor assets
 */
function aberdeen_block_editor_assets() {
	wp_enqueue_script(
		'aberdeen-editor-blocks',
		get_template_directory_uri() . '/assets/js/editor-blocks.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		'1.0.0',
		true
	);

	wp_localize_script( 'aberdeen-editor-blocks', 'AberdeenThemeData', array(
		'templateUrl' => get_template_directory_uri(),
		'defaultImages' => array(
			'heroSkyline'      => content_url( '/uploads/2026/10/97ee14467ba2db928b85ff8c65f82ab7718d4ea9.jpg' ),
			'careersStudents'  => content_url( '/uploads/2026/10/2b71e6559542c9d76909b366948c3f60c0486b9a.png' ),
			'aiUndergrad'      => content_url( '/uploads/2026/10/96e55d488c6122d7227906856b19651b9501832a.png' ),
			'lecturePostgrad'  => content_url( '/uploads/2026/10/1f1cfbfa70f757fb0fc013dc99166d566c66d42c.jpg' ),
		),
		'urls' => array(
			'home'          => home_url( '/' ),
			'programmes'    => home_url( '/programmes' ),
			'undergraduate' => home_url( '/programmes/undergraduate' ),
			'postgraduate'  => home_url( '/programmes/postgraduate' ),
		),
	) );

	wp_enqueue_style(
		'aberdeen-editor-blocks-style',
		get_template_directory_uri() . '/assets/css/editor-blocks.css',
		array( 'wp-edit-blocks' ),
		'1.0.0'
	);
}
add_action( 'enqueue_block_editor_assets', 'aberdeen_block_editor_assets' );

/**
 * Helper to ensure block URLs properly respect the WordPress home_url()
 * and site directory (e.g. /aum/) so links never point outside the installation.
 */
function aberdeen_format_url( $url, $fallback = '' ) {
	$target = ! empty( $url ) ? trim( $url ) : $fallback;

	if ( empty( $target ) ) {
		return home_url( '/' );
	}

	// Anchors, tel, mailto
	if ( 0 === strpos( $target, '#' ) || 0 === strpos( $target, 'mailto:' ) || 0 === strpos( $target, 'tel:' ) ) {
		return $target;
	}

	// Full absolute URLs
	if ( preg_match( '#^https?://#i', $target ) ) {
		$home       = home_url();
		$home_path  = trim( (string) wp_parse_url( $home, PHP_URL_PATH ), '/' );
		$target_url = wp_parse_url( $target );
		$home_url_p = wp_parse_url( $home );

		// If it points to the same domain (e.g. localhost) but omitted the subfolder /aum
		if ( ! empty( $home_path ) && isset( $target_url['host'] ) && $target_url['host'] === $home_url_p['host'] ) {
			$t_path = isset( $target_url['path'] ) ? trim( $target_url['path'], '/' ) : '';
			if ( 0 !== strpos( $t_path, $home_path ) ) {
				return home_url( '/' . $t_path );
			}
		}
		return $target;
	}

	// Root-relative or relative paths (e.g. '/programmes' or 'programmes/undergraduate')
	return home_url( '/' . ltrim( $target, '/' ) );
}

/**
 * 1. Render callback for Hero Slider block
 */
function aberdeen_render_hero_slider_block( $attributes ) {
	$headline   = ! empty( $attributes['headline'] ) ? $attributes['headline'] : 'Join the University of Aberdeen in India';
	$subtext    = ! empty( $attributes['subtext'] ) ? $attributes['subtext'] : 'Global challenges are waiting for new ways of thinking. Discover your true north and start creating a more inclusive and sustainable world';
	$link_url   = aberdeen_format_url( ! empty( $attributes['linkUrl'] ) ? $attributes['linkUrl'] : '', '/programmes' );
	$skyline_file = get_template_directory() . '/assets/images/mumbai-skyline.jpg';
	$skyline_ver  = file_exists( $skyline_file ) ? filemtime( $skyline_file ) : '1.0';
	$image_url    = ! empty( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : ( get_template_directory_uri() . '/assets/images/mumbai-skyline.jpg?v=' . $skyline_ver );
	$slides_json = ! empty( $attributes['slidesJson'] ) ? json_decode( $attributes['slidesJson'], true ) : null;

	$slides = array(
		array(
			'headline'  => $headline,
			'subtext'   => $subtext,
			'link_url'  => $link_url,
			'image_url' => $image_url,
		),
	);

	if ( ! empty( $slides_json ) && is_array( $slides_json ) ) {
		$slides = $slides_json;
	}

	ob_start();
	?>
	<section class="hero-slider-section" id="hero-slider" aria-label="Hero Slideshow">
		<div class="slider-wrapper">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php
				$slide_bg   = ! empty( $slide['image_url'] ) ? $slide['image_url'] : $image_url;
				$slide_head = ! empty( $slide['headline'] ) ? $slide['headline'] : $headline;
				$slide_sub  = ! empty( $slide['subtext'] ) ? $slide['subtext'] : $subtext;
				$slide_link = aberdeen_format_url( ! empty( $slide['link_url'] ) ? $slide['link_url'] : $link_url, '/programmes' );
				?>
				<div class="slide-item <?php echo ( 0 === $index ) ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url( $slide_bg ); ?>');">
					<div class="hero-bottom-bar">
						<div class="hero-content-container">
							<h1 class="hero-headline"><?php echo esc_html( $slide_head ); ?></h1>
							<p class="hero-subtext">
								<?php echo esc_html( $slide_sub ); ?>
								<a href="<?php echo esc_url( $slide_link ); ?>" class="hero-inline-link" <?php echo program_track_attr( 'link', 'hero-discover-more', 'hero-section', 'componentLink' ); ?>>
									<span class="hero-arrow-gold">&#10148;</span>
								</a>
							</p>
							<div class="slider-dots" id="sliderDots" role="tablist" aria-label="Hero Slides">
								<button type="button" class="slider-dot active" data-slide-index="0" aria-label="Slide 1" <?php echo program_track_attr( 'button', 'hero-dot-1', 'hero-section', 'componentButton' ); ?>></button>
								<button type="button" class="slider-dot hollow" data-slide-index="1" aria-label="Slide 2" <?php echo program_track_attr( 'button', 'hero-dot-2', 'hero-section', 'componentButton' ); ?>></button>
								<button type="button" class="slider-dot hollow" data-slide-index="2" aria-label="Slide 3" <?php echo program_track_attr( 'button', 'hero-dot-3', 'hero-section', 'componentButton' ); ?>></button>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			<!-- Slider Controls: Sleek square arrows matching attached Figma design -->
			<button type="button" class="slider-arrow slider-arrow-prev" id="sliderPrevBtn" aria-label="Previous Slide" <?php echo program_track_attr( 'button', 'hero-slider-prev', 'hero-section', 'componentButton' ); ?>>
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#25A282" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="15 18 9 12 15 6"></polyline>
				</svg>
			</button>
			<button type="button" class="slider-arrow slider-arrow-next" id="sliderNextBtn" aria-label="Next Slide" <?php echo program_track_attr( 'button', 'hero-slider-next', 'hero-section', 'componentButton' ); ?>>
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#25A282" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round">
					<polyline points="9 18 15 12 9 6"></polyline>
				</svg>
			</button>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * 2. Render callback for Careers and Employability block
 */
function aberdeen_render_careers_block( $attributes ) {
	$title       = ! empty( $attributes['title'] ) ? $attributes['title'] : 'Careers and Employability';
	$default_img = content_url( '/uploads/2026/10/2b71e6559542c9d76909b366948c3f60c0486b9a.png' );
	if ( ! file_exists( WP_CONTENT_DIR . '/uploads/2026/10/2b71e6559542c9d76909b366948c3f60c0486b9a.png' ) ) {
		$default_img = get_template_directory_uri() . '/assets/images/careers-students.jpg';
	}
	$image_url   = ! empty( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : $default_img;
	$p1          = ! empty( $attributes['paragraph1'] ) ? $attributes['paragraph1'] : 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
	$p2          = ! empty( $attributes['paragraph2'] ) ? $attributes['paragraph2'] : 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.';
	$button_text = ! empty( $attributes['buttonText'] ) ? $attributes['buttonText'] : 'See More';
	$button_url  = ! empty( $attributes['buttonUrl'] ) ? $attributes['buttonUrl'] : home_url( '/programmes' );

	ob_start();
	?>
	<section class="careers-section" id="careers-and-employability">
		<div class="careers-container">
			<div class="careers-header-wrap">
				<h2 class="section-heading-dark"><?php echo esc_html( $title ); ?></h2>
				<div class="accent-underline-gold"></div>
			</div>

			<div class="careers-main-grid">
				<div class="careers-image-column">
					<div class="media-single-card">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="careers-single-img" loading="lazy">
					</div>
				</div>

				<div class="careers-content-column">
					<div class="careers-text-body">
						<p><?php echo esc_html( $p1 ); ?></p>
						<p><?php echo esc_html( $p2 ); ?></p>
					</div>
					<div class="careers-action">
						<a href="<?php echo esc_url( aberdeen_format_url( $button_url, '/programmes' ) ); ?>" class="btn btn-gold btn-see-more" <?php echo program_track_attr( 'link', 'careers-see-more', 'careers-section', 'componentLink' ); ?>>
							<?php echo esc_html( $button_text ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * 3. Render callback for Our Programmes block
 */
function aberdeen_render_our_programmes_block( $attributes ) {
	$title        = ! empty( $attributes['title'] ) ? $attributes['title'] : 'Our Programmes';
	$card1_title  = ! empty( $attributes['card1Title'] ) ? $attributes['card1Title'] : 'Undergraduate Programmes';
	$card1_desc   = ! empty( $attributes['card1Desc'] ) ? $attributes['card1Desc'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.';
	$card1_image  = ! empty( $attributes['card1Image'] ) ? $attributes['card1Image'] : get_template_directory_uri() . '/assets/images/ai-undergrad.jpg';
	$card1_link   = aberdeen_format_url( ! empty( $attributes['card1Link'] ) ? $attributes['card1Link'] : '', '/programmes/undergraduate' );

	$card2_title  = ! empty( $attributes['card2Title'] ) ? $attributes['card2Title'] : 'Postgraduate Programmes';
	$card2_desc   = ! empty( $attributes['card2Desc'] ) ? $attributes['card2Desc'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.';
	$card2_image  = ! empty( $attributes['card2Image'] ) ? $attributes['card2Image'] : get_template_directory_uri() . '/assets/images/lecture-postgrad.jpg';
	$card2_link   = aberdeen_format_url( ! empty( $attributes['card2Link'] ) ? $attributes['card2Link'] : '', '/programmes/postgraduate' );

	ob_start();
	?>
	<section class="our-programmes-section" id="our-programmes">
		<div class="programmes-section-container">
			<div class="programmes-header-center">
				<h2 class="section-heading-gold"><?php echo esc_html( $title ); ?></h2>
				<div class="accent-underline-gold-center"></div>
			</div>

			<div class="programmes-category-grid">
				<!-- Undergraduate Card -->
				<div class="programme-cat-card">
					<div class="cat-card-image-wrap">
						<img src="<?php echo esc_url( $card1_image ); ?>" alt="<?php echo esc_attr( $card1_title ); ?>" class="cat-card-img" loading="lazy">
					</div>
					<div class="cat-card-body">
						<h3 class="cat-card-title"><?php echo esc_html( $card1_title ); ?></h3>
						<p class="cat-card-desc"><?php echo esc_html( $card1_desc ); ?></p>
						<div class="cat-card-footer">
							<a href="<?php echo esc_url( $card1_link ); ?>" class="cat-card-link" <?php echo program_track_attr( 'link', 'see-more-undergraduate', 'our-programmes-section', 'componentLink' ); ?>>
								<span>See More</span>
								<span class="arrow">&#10148;</span>
							</a>
						</div>
					</div>
				</div>

				<!-- Postgraduate Card -->
				<div class="programme-cat-card">
					<div class="cat-card-image-wrap">
						<img src="<?php echo esc_url( $card2_image ); ?>" alt="<?php echo esc_attr( $card2_title ); ?>" class="cat-card-img" loading="lazy">
					</div>
					<div class="cat-card-body">
						<h3 class="cat-card-title"><?php echo esc_html( $card2_title ); ?></h3>
						<p class="cat-card-desc"><?php echo esc_html( $card2_desc ); ?></p>
						<div class="cat-card-footer">
							<a href="<?php echo esc_url( $card2_link ); ?>" class="cat-card-link" <?php echo program_track_attr( 'link', 'see-more-postgraduate', 'our-programmes-section', 'componentLink' ); ?>>
								<span>See More</span>
								<span class="arrow">&#10148;</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

/**
 * Register blocks on init
 */
function aberdeen_register_custom_blocks() {
	register_block_type( 'aberdeen/hero-slider', array(
		'render_callback' => 'aberdeen_render_hero_slider_block',
		'attributes'      => array(
			'headline'   => array( 'type' => 'string', 'default' => 'Join the University of Aberdeen in India' ),
			'subtext'    => array( 'type' => 'string', 'default' => 'Global challenges are waiting for new ways of thinking. Discover your true north and start creating a more inclusive and sustainable world' ),
			'linkUrl'    => array( 'type' => 'string', 'default' => '/programmes' ),
			'imageUrl'   => array( 'type' => 'string', 'default' => '' ),
			'slidesJson' => array( 'type' => 'string', 'default' => '' ),
		),
	) );

	register_block_type( 'aberdeen/careers-section', array(
		'render_callback' => 'aberdeen_render_careers_block',
		'attributes'      => array(
			'title'      => array( 'type' => 'string', 'default' => 'Careers and Employability' ),
			'imageUrl'   => array( 'type' => 'string', 'default' => '' ),
			'paragraph1' => array( 'type' => 'string', 'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' ),
			'paragraph2' => array( 'type' => 'string', 'default' => 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.' ),
			'buttonText' => array( 'type' => 'string', 'default' => 'See More' ),
			'buttonUrl'  => array( 'type' => 'string', 'default' => '/programmes' ),
		),
	) );

	register_block_type( 'aberdeen/our-programmes', array(
		'render_callback' => 'aberdeen_render_our_programmes_block',
		'attributes'      => array(
			'title'       => array( 'type' => 'string', 'default' => 'Our Programmes' ),
			'card1Title'  => array( 'type' => 'string', 'default' => 'Undergraduate Programmes' ),
			'card1Desc'   => array( 'type' => 'string', 'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.' ),
			'card1Image'  => array( 'type' => 'string', 'default' => '' ),
			'card1Link'   => array( 'type' => 'string', 'default' => '/programmes/undergraduate' ),
			'card2Title'  => array( 'type' => 'string', 'default' => 'Postgraduate Programmes' ),
			'card2Desc'   => array( 'type' => 'string', 'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.' ),
			'card2Image'  => array( 'type' => 'string', 'default' => '' ),
			'card2Link'   => array( 'type' => 'string', 'default' => '/programmes/postgraduate' ),
		),
	) );
}
add_action( 'init', 'aberdeen_register_custom_blocks' );
