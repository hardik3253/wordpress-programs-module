<?php
/**
 * Front Page Template - Matching Figma Desktop P1
 */
get_header();
?>

<!-- 1. Hero Slideshow Section -->
<section class="hero-slider-section" id="hero-slider" aria-label="Hero Slideshow">
	<div class="slider-wrapper">
		<!-- Slide 1 -->
		<div class="slide-item active" style="background-image: linear-gradient(rgba(8, 24, 43, 0.45), rgba(8, 24, 43, 0.75)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1920&q=80');">
			<div class="hero-content-container">
				<h1 class="hero-headline">Join the University of Aberdeen in India</h1>
				<p class="hero-subtext">
					Global challenges are waiting for new ways of thinking. Discover your true north and start creating a more inclusive and sustainable world 
					<a href="<?php echo esc_url( home_url( '/programmes' ) ); ?>" class="hero-inline-link" <?php echo program_track_attr( 'link', 'hero-discover-true-north', 'hero-section', 'componentLink' ); ?>>&rarr;</a>
				</p>
			</div>
		</div>

		<!-- Slide 2 -->
		<div class="slide-item" style="background-image: linear-gradient(rgba(8, 24, 43, 0.45), rgba(8, 24, 43, 0.75)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=1920&q=80');">
			<div class="hero-content-container">
				<h2 class="hero-headline">World-Class Scottish Heritage in Mumbai</h2>
				<p class="hero-subtext">
					Over 500 years of academic excellence now delivering internationally recognized degrees in India.
					<a href="<?php echo esc_url( home_url( '/programmes' ) ); ?>" class="hero-inline-link" <?php echo program_track_attr( 'link', 'hero-heritage-explore', 'hero-section', 'componentLink' ); ?>>&rarr;</a>
				</p>
			</div>
		</div>

		<!-- Slide 3 -->
		<div class="slide-item" style="background-image: linear-gradient(rgba(8, 24, 43, 0.45), rgba(8, 24, 43, 0.75)), url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1920&q=80');">
			<div class="hero-content-container">
				<h2 class="hero-headline">Empowering Tomorrow's Technology Leaders</h2>
				<p class="hero-subtext">
					Cutting-edge curriculum in AI, Computing, and Modern Engineering tailored for future innovation.
					<a href="<?php echo esc_url( home_url( '/programmes' ) ); ?>" class="hero-inline-link" <?php echo program_track_attr( 'link', 'hero-innovation-explore', 'hero-section', 'componentLink' ); ?>>&rarr;</a>
				</p>
			</div>
		</div>

		<!-- Slider Controls: Prev / Next buttons -->
		<button type="button" class="slider-arrow slider-arrow-prev" id="sliderPrevBtn" aria-label="Previous Slide" <?php echo program_track_attr( 'button', 'hero-slider-prev', 'hero-section', 'componentButton' ); ?>>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="15 18 9 12 15 6"></polyline>
			</svg>
		</button>
		<button type="button" class="slider-arrow slider-arrow-next" id="sliderNextBtn" aria-label="Next Slide" <?php echo program_track_attr( 'button', 'hero-slider-next', 'hero-section', 'componentButton' ); ?>>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
				<polyline points="9 18 15 12 9 6"></polyline>
			</svg>
		</button>

		<!-- Pagination Dots -->
		<div class="slider-dots" id="sliderDots" role="tablist">
			<button type="button" class="slider-dot active" data-slide-index="0" aria-label="Slide 1" <?php echo program_track_attr( 'button', 'hero-slider-dot-1', 'hero-section', 'componentButton' ); ?>></button>
			<button type="button" class="slider-dot" data-slide-index="1" aria-label="Slide 2" <?php echo program_track_attr( 'button', 'hero-slider-dot-2', 'hero-section', 'componentButton' ); ?>></button>
			<button type="button" class="slider-dot" data-slide-index="2" aria-label="Slide 3" <?php echo program_track_attr( 'button', 'hero-slider-dot-3', 'hero-section', 'componentButton' ); ?>></button>
		</div>
	</div>
</section>

<!-- 2. Careers and Employability Section -->
<section class="careers-section" id="about-us">
	<div class="careers-container">
		<div class="careers-media-grid">
			<div class="media-card media-primary">
				<img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80" alt="Students in campus discussing coursework" class="careers-img" loading="lazy">
			</div>
			<div class="media-card media-secondary">
				<img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80" alt="Collaborative study session" class="careers-img" loading="lazy">
			</div>
		</div>

		<div class="careers-content">
			<h2 class="section-heading-dark">Careers and Employability</h2>
			<div class="careers-text-body">
				<p>
					Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
				</p>
				<p>
					Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
				</p>
			</div>
			<div class="careers-action">
				<a href="<?php echo esc_url( home_url( '/programmes' ) ); ?>" class="btn btn-gold btn-see-more" <?php echo program_track_attr( 'link', 'careers-see-more', 'careers-section', 'componentLink' ); ?>>
					See More
				</a>
			</div>
		</div>
	</div>
</section>

<!-- 3. Our Programmes Section -->
<section class="our-programmes-section" id="study">
	<div class="programmes-section-container">
		<h2 class="section-heading-gold">Our Programmes</h2>

		<div class="programmes-category-grid">
			<!-- Undergraduate Card -->
			<div class="programme-cat-card">
				<div class="cat-card-image-wrap">
					<img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80" alt="Undergraduate student with AI and laptop" class="cat-card-img" loading="lazy">
				</div>
				<div class="cat-card-body">
					<h3 class="cat-card-title">Undergraduate Programmes</h3>
					<p class="cat-card-desc">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.
					</p>
					<a href="<?php echo esc_url( home_url( '/programmes/undergraduate' ) ); ?>" class="cat-card-link" <?php echo program_track_attr( 'link', 'see-more-undergraduate', 'our-programmes-section', 'componentLink' ); ?>>
						See More <span class="arrow">&rarr;</span>
					</a>
				</div>
			</div>

			<!-- Postgraduate Card -->
			<div class="programme-cat-card">
				<div class="cat-card-image-wrap">
					<img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&q=80" alt="Postgraduate lecture in modern classroom" class="cat-card-img" loading="lazy">
				</div>
				<div class="cat-card-body">
					<h3 class="cat-card-title">Postgraduate Programmes</h3>
					<p class="cat-card-desc">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante tortor.
					</p>
					<a href="<?php echo esc_url( home_url( '/programmes/postgraduate' ) ); ?>" class="cat-card-link" <?php echo program_track_attr( 'link', 'see-more-postgraduate', 'our-programmes-section', 'componentLink' ); ?>>
						See More <span class="arrow">&rarr;</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
