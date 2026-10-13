<?php
/**
 * Reusable Program Card Template Part (Task 5)
 *
 * Arguments passed via $args array:
 * - 'post_id'       (int) Optional post ID, defaults to get_the_ID()
 * - 'title'         (string) Optional override
 * - 'permalink'     (string) Optional override
 * - 'thumbnail_url' (string) Optional override
 * - 'academic_year' (string) Optional override
 * - 'duration'      (string) Optional override
 * - 'excerpt'       (string) Optional override
 * - 'term_slug'     (string) Optional override
 * - 'track_section' (string) Optional tracking section context, default 'programme-listing'
 */

$card_post_id = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
if ( ! $card_post_id ) {
	return;
}

$title         = isset( $args['title'] ) ? $args['title'] : get_the_title( $card_post_id );
$permalink     = isset( $args['permalink'] ) ? $args['permalink'] : get_permalink( $card_post_id );
$academic_year = isset( $args['academic_year'] ) ? $args['academic_year'] : get_post_meta( $card_post_id, 'academic_year', true );
$duration      = isset( $args['duration'] ) ? $args['duration'] : get_post_meta( $card_post_id, 'duration', true );
$excerpt       = isset( $args['excerpt'] ) ? $args['excerpt'] : get_the_excerpt( $card_post_id );
$thumbnail_url = isset( $args['thumbnail_url'] ) ? $args['thumbnail_url'] : get_the_post_thumbnail_url( $card_post_id, 'medium_large' );
$track_section = isset( $args['track_section'] ) ? $args['track_section'] : 'programme-listing';

// High-resolution curated fallback images matching Aberdeen university programs
if ( empty( $thumbnail_url ) ) {
	$curated_images = array(
		'bachelor-of-computer-applications-bca'    => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80',
		'bachelor-of-engineering-be'                => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=800&q=80',
		'bsc-artificial-intelligence-data-science' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80',
		'bachelor-of-business-administration-bba'   => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80',
		'bsc-cyber-security-digital-forensics'     => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=80',
		'master-of-computer-applications-mca'      => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=800&q=80',
		'master-of-engineering-me'                 => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
		'msc-data-science-business-analytics'      => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
		'mba-international-business'               => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=800&q=80',
		'msc-cloud-computing-devops'               => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
	);

	$post_slug = get_post_field( 'post_name', $card_post_id );
	if ( isset( $curated_images[ $post_slug ] ) ) {
		$thumbnail_url = $curated_images[ $post_slug ];
	} else {
		$thumbnail_url = 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80';
	}
}

$terms     = get_the_terms( $card_post_id, 'program-type' );
$term_slug = isset( $args['term_slug'] ) ? $args['term_slug'] : ( ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->slug : 'all' );
$term_name = ( ! empty( $terms ) && ! is_wp_error( $terms ) ) ? $terms[0]->name : ucfirst( $term_slug );
?>
<article class="program-card" data-program-type="<?php echo esc_attr( $term_slug ); ?>">
	<a href="<?php echo esc_url( $permalink ); ?>" 
	   class="program-card-link" 
	   <?php echo function_exists( 'program_track_attr' ) ? program_track_attr( 'link', 'program-card-' . sanitize_title( $title ), $track_section, 'componentCard' ) : ''; ?>>
		
		<div class="program-card-image-wrap">
			<img src="<?php echo esc_url( $thumbnail_url ); ?>" 
			     alt="<?php echo esc_attr( $title ); ?>" 
			     class="program-card-image" 
			     loading="lazy">
			<?php if ( ! empty( $term_slug ) && 'all' !== $term_slug ) : ?>
				<span class="program-card-badge"><?php echo esc_html( $term_name ); ?></span>
			<?php endif; ?>
		</div>

		<div class="program-card-content">
			<h3 class="program-card-title"><?php echo esc_html( $title ); ?></h3>
			<div class="program-card-divider"></div>
			
			<?php if ( ! empty( $excerpt ) ) : ?>
				<p class="program-card-excerpt"><?php echo esc_html( $excerpt ); ?></p>
			<?php endif; ?>

			<div class="program-card-meta">
				<?php if ( ! empty( $academic_year ) ) : ?>
					<div class="meta-item">
						<span class="meta-label">Academic Year:</span>
						<span class="meta-value"><?php echo esc_html( $academic_year ); ?></span>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $duration ) ) : ?>
					<div class="meta-item">
						<span class="meta-label">Duration:</span>
						<span class="meta-value"><?php echo esc_html( $duration ); ?></span>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="program-card-action">
				<span class="btn-card-details">View Details &rarr;</span>
			</div>
		</div>
	</a>
</article>
