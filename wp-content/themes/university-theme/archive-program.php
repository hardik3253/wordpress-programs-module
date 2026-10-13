<?php
/**
 * Archive / Programs Listing Template - Matching Figma Desktop P2
 * Supports Task 4 (Routing), Task 5 (Reusable Card), Task 7 (Interactivity API Filter), and Task 8 (Tracking)
 */
get_header();

// Determine current filter from query var or URL
$active_filter = get_query_var( 'program_type_filter' );
if ( empty( $active_filter ) ) {
	$active_filter = 'all';
} else {
	$active_filter = sanitize_title( $active_filter );
}

$page_title = 'Our Programmes';
if ( 'undergraduate' === $active_filter ) {
	$page_title = 'Undergraduate Programmes';
} elseif ( 'postgraduate' === $active_filter ) {
	$page_title = 'Postgraduate Programmes';
}

$programs = university_get_programs( $active_filter );
?>

<div class="programmes-archive-page" id="programmes-archive-page">
	<!-- Hero Page Title Banner (Dark Navy) matching Figma -->
	<header class="page-intro-header">
		<div class="page-intro-container">
			<h1 class="page-main-heading" id="programmesPageHeading"><?php echo esc_html( $page_title ); ?></h1>
			<div class="accent-underline-header"></div>
		</div>
	</header>

	<!-- White Intro Subtitle Strip matching Figma -->
	<div class="page-lead-strip">
		<div class="page-lead-container">
			<p class="page-lead-subtitle">
				Discover a campus that blends Scottish heritage with Indian innovation. A global education awaits!
			</p>
			<?php if ( 'all' === $active_filter ) : ?>
				<!-- Filter Bar for All Programmes View -->
				<nav class="program-filter-bar" aria-label="Program Filter">
					<a href="<?php echo esc_url( home_url( '/programmes' ) ); ?>" 
					   class="filter-pill <?php echo ( 'all' === $active_filter ) ? 'active' : ''; ?>" 
					   data-filter-slug="all"
					   <?php echo program_track_attr( 'link', 'filter-all', 'programme-filter-bar', 'componentLink' ); ?>>
						All
					</a>
					<a href="<?php echo esc_url( home_url( '/programmes/undergraduate' ) ); ?>" 
					   class="filter-pill <?php echo ( 'undergraduate' === $active_filter ) ? 'active' : ''; ?>" 
					   data-filter-slug="undergraduate"
					   <?php echo program_track_attr( 'link', 'filter-undergraduate', 'programme-filter-bar', 'componentLink' ); ?>>
						Undergraduate
					</a>
					<a href="<?php echo esc_url( home_url( '/programmes/postgraduate' ) ); ?>" 
					   class="filter-pill <?php echo ( 'postgraduate' === $active_filter ) ? 'active' : ''; ?>" 
					   data-filter-slug="postgraduate"
					   <?php echo program_track_attr( 'link', 'filter-postgraduate', 'programme-filter-bar', 'componentLink' ); ?>>
						Postgraduate
					</a>
				</nav>
			<?php endif; ?>
		</div>
	</div>

	<!-- Programs Grid Section -->
	<section class="programs-listing-section">
		<div class="programs-listing-container">
			<!-- Loading State indicator (Task 7) -->
			<div class="filter-loading-spinner" id="filterLoadingSpinner" style="display: none;" aria-live="polite">
				<div class="spinner"></div>
				<span>Loading programmes...</span>
			</div>

			<!-- Cards Container -->
			<div class="programs-card-grid" id="programsGrid">
				<?php if ( ! empty( $programs ) ) : ?>
					<?php foreach ( $programs as $program_post ) : ?>
						<?php render_program_card( $program_post->ID ); ?>
					<?php endforeach; ?>
				<?php else : ?>
					<!-- Empty State (Task 7) -->
					<div class="filter-empty-state" id="filterEmptyState">
						<h3>No programmes found</h3>
						<p>There are currently no programmes listed in this category. Please check back soon.</p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</div>

<?php
get_footer();
