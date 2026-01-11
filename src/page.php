<?php
/**
 * The template for displaying pages
 *
 * @package Theme_WordPress
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-lg-10 mx-auto">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="page-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
					<header class="entry-header mb-4 pb-3 border-bottom">
						<h1 class="entry-title display-4"><?php the_title(); ?></h1>

						<?php if ( has_excerpt() ) : ?>
							<div class="entry-excerpt lead text-muted mt-3">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="entry-thumbnail mb-4">
							<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded' ) ); ?>
							<?php if ( get_the_post_thumbnail_caption() ) : ?>
								<figcaption class="text-muted mt-2 small">
									<?php echo esc_html( get_the_post_thumbnail_caption() ); ?>
								</figcaption>
							<?php endif; ?>
						</figure>
					<?php endif; ?>

					<div class="entry-content">
						<?php
						the_content();

						wp_link_pages( array(
							'before'      => '<nav class="page-links mt-4 mb-4"><span class="page-links-title">' . __( 'Páginas:', 'theme-wordpress' ) . '</span>',
							'after'       => '</nav>',
							'link_before' => '<span class="badge bg-secondary me-1">',
							'link_after'  => '</span>',
						) );
						?>
					</div>
				</article>

			<?php endwhile; ?>
		</div>
	</div>
</div>

<?php
get_footer();
