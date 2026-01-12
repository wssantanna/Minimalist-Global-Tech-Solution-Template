<?php
/**
 * The template for displaying single posts
 *
 * @package Theme_WordPress
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-lg-12 mx-auto">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>
					<header class="entry-header mb-4">
						<h1 class="entry-title display-4 fw-semibold mb-3"><?php the_title(); ?></h1>

						<div class="entry-meta text-muted mb-4 small">
							<span class="me-2">
								<?php
								printf(
									/* translators: %s: post author */
									__( 'Por %s', 'theme-wordpress' ),
									'<strong>' . esc_html( get_the_author() ) . '</strong>'
								);
								?>
							</span>
							<span class="me-2">—</span>
							<span class="me-2">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?>
								</time>
							</span>
							<span class="me-2">—</span>
							<span>
								<?php
								$content = get_the_content();
								$word_count = str_word_count( strip_tags( $content ) );
								$reading_time = ceil( $word_count / 200 );
								printf(
									/* translators: %d: reading time in minutes */
									_n( '%d minuto de leitura', '%d minutos de leitura', $reading_time, 'theme-wordpress' ),
									$reading_time
								);
								?>
							</span>
						</div>
					</header>

					<div class="entry-content fs-5 lh-lg">
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
