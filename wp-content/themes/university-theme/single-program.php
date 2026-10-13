<?php
/**
 * Single Program Detail Template (Task 6)
 */
get_header();

$post_id          = get_the_ID();
$title            = get_the_title();
$content          = get_the_content();
$academic_year    = get_post_meta( $post_id, 'academic_year', true );
$duration         = get_post_meta( $post_id, 'duration', true );
$content_sections = get_post_meta( $post_id, 'content_sections', true );
$thumbnail_url    = get_the_post_thumbnail_url( $post_id, 'large' );

if ( empty( $thumbnail_url ) ) {
	$thumbnail_url = 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80';
}

$terms = get_the_terms( $post_id, 'program-type' );
$term_obj = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0] : null;
$term_link = $term_obj ? home_url( '/programmes/' . $term_obj->slug ) : home_url( '/programmes' );
$term_name = $term_obj ? $term_obj->name : 'Programmes';
?>

<article class="single-program-article" id="single-program-article">
	<!-- Hero / Header Banner -->
	<header class="program-detail-hero">
		<div class="detail-hero-container">
			<!-- Breadcrumb and Back Link -->
			<div class="detail-navigation-bar">
				<a href="<?php echo esc_url( home_url( '/programmes' ) ); ?>" class="back-link" <?php echo program_track_attr( 'link', 'back-to-programmes', 'programme-overview', 'componentLink' ); ?>>
					&larr; Back to programmes
				</a>
				<nav class="breadcrumb-trail" aria-label="Breadcrumb">
					<?php
					$breadcrumbs = university_get_breadcrumbs();
					$last_index  = count( $breadcrumbs ) - 1;
					foreach ( $breadcrumbs as $i => $crumb ) :
						if ( ! empty( $crumb['url'] ) && $i < $last_index ) :
							?>
							<a href="<?php echo esc_url( $crumb['url'] ); ?>" <?php echo ! empty( $crumb['track'] ) ? program_track_attr( 'link', $crumb['track'], 'programme-overview', 'componentLink' ) : ''; ?>>
								<?php echo esc_html( $crumb['label'] ); ?>
							</a>
							<span class="breadcrumb-sep">&gt;</span>
						<?php else : ?>
							<span class="breadcrumb-current"><?php echo esc_html( $crumb['label'] ); ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</nav>
			</div>

			<div class="detail-hero-layout">
				<div class="detail-hero-info">
					<?php if ( $term_obj ) : ?>
						<a href="<?php echo esc_url( $term_link ); ?>" class="program-type-badge-link" <?php echo program_track_attr( 'link', 'detail-badge-' . sanitize_title( $term_name ), 'programme-overview', 'componentLink' ); ?>>
							<?php echo esc_html( $term_name ); ?>
						</a>
					<?php endif; ?>

					<h1 class="program-detail-title"><?php echo esc_html( $title ); ?></h1>

					<!-- Meta Badges -->
					<div class="program-hero-meta-badges">
						<?php if ( ! empty( $academic_year ) ) : ?>
							<div class="hero-badge">
								<span class="badge-icon">&#128197;</span>
								<span class="badge-label">Academic Year:</span>
								<strong><?php echo esc_html( $academic_year ); ?></strong>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $duration ) ) : ?>
							<div class="hero-badge">
								<span class="badge-icon">&#9200;</span>
								<span class="badge-label">Duration:</span>
								<strong><?php echo esc_html( $duration ); ?></strong>
							</div>
						<?php endif; ?>
					</div>

					<!-- CTAs (Task 6) -->
					<div class="program-action-buttons">
						<a href="#leadModalOverlay" class="btn btn-gold btn-apply-now js-open-lead-modal" <?php echo program_track_attr( 'link', 'apply-now', 'programme-overview', 'componentLink' ); ?>>
							Apply now
						</a>
						<button type="button" class="btn btn-outline-light btn-enquire-now js-open-lead-modal" <?php echo program_track_attr( 'button', 'open-enquiry-form', 'programme-overview', 'componentButton' ); ?>>
							Enquire now
						</button>
					</div>
				</div>

				<div class="detail-hero-media">
					<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" class="detail-featured-image">
				</div>
			</div>
		</div>
	</header>

	<!-- Main Content & Dynamic Sections -->
	<section class="program-detail-body">
		<div class="detail-body-container">
			<!-- Main Overview Content -->
			<div class="program-overview-card">
				<h2 class="section-subheading">Programme Overview</h2>
				<div class="program-main-text">
					<?php echo wp_kses_post( wpautop( $content ) ); ?>
				</div>
			</div>

			<!-- Dynamic Repeater Content Sections -->
			<?php if ( ! empty( $content_sections ) && is_array( $content_sections ) ) : ?>
				<div class="program-sections-list">
					<?php foreach ( $content_sections as $index => $sec ) : ?>
						<?php
						$sec_title = isset( $sec['title'] ) ? $sec['title'] : '';
						$sec_desc  = isset( $sec['description'] ) ? $sec['description'] : '';
						$sec_img   = isset( $sec['image'] ) ? $sec['image'] : '';
						?>
						<div class="repeater-section-card" id="section-<?php echo esc_attr( $index ); ?>">
							<h3 class="repeater-section-title"><?php echo esc_html( $sec_title ); ?></h3>
							<div class="repeater-section-body">
								<p><?php echo esc_html( $sec_desc ); ?></p>
								<?php if ( ! empty( $sec_img ) ) : ?>
									<div class="repeater-section-image">
										<img src="<?php echo esc_url( $sec_img ); ?>" alt="<?php echo esc_attr( $sec_title ); ?>">
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<!-- Bottom CTA Banner -->
			<div class="detail-bottom-cta">
				<div class="cta-box">
					<h3>Ready to take the next step at Aberdeen Mumbai?</h3>
					<p>Applications for the 2025-26 academic year are currently open.</p>
					<button type="button" class="btn btn-gold js-open-lead-modal" <?php echo program_track_attr( 'button', 'bottom-cta-register-interest', 'programme-overview', 'componentButton' ); ?>>
						Register Your Interest
					</button>
				</div>
			</div>
		</div>
	</section>
</article>

<?php
get_footer();
