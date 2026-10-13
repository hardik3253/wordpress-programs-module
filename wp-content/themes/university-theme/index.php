<?php
/**
 * Main Template Fallback
 */
get_header();
?>

<div class="generic-content-container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'university-theme' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
