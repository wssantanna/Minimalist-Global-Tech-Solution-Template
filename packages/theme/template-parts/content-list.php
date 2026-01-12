<?php
/**
 * Template part for displaying posts in list format (Bootstrap 5)
 *
 * @package Theme_WordPress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-4' ); ?>>
	<div class="card border-0 h-100">
		<div class="row g-0 h-100">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="col-md-4">
					<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ir para %s', 'theme-wordpress' ), get_the_title() ) ); ?>">
						<?php
						the_post_thumbnail( 'theme-card', array(
							'class' => 'img-fluid rounded-start w-100 h-100 object-fit-cover',
							'alt'   => the_title_attribute( array( 'echo' => false ) ),
						) );
						?>
					</a>
				</div>
			<?php endif; ?>

			<div class="<?php echo has_post_thumbnail() ? 'col-md-8' : 'col-12'; ?>">
				<div class="card-body d-flex flex-column h-100 p-0">
					<h2 class="h5 fw-bold mb-2">
						<a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
							<?php the_title(); ?>
						</a>
					</h2>

					<div class="text-muted small mb-3">
						<?php if ( get_theme_mod( 'theme_show_post_author', true ) ) : ?>
							<span>
								<?php
								printf(
									/* translators: %s: post author */
									__( 'Por %s', 'theme-wordpress' ),
									esc_html( get_the_author() )
								);
								?>
							</span>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'theme_show_post_author', true ) && get_theme_mod( 'theme_show_post_date', true ) ) : ?>
							<span class="mx-2">—</span>
						<?php endif; ?>

						<?php if ( get_theme_mod( 'theme_show_post_date', true ) ) : ?>
							<span>
								<?php
								printf(
									'<time datetime="%s">%s</time>',
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date( 'd/m/Y' ) )
								);
								?>
							</span>
						<?php endif; ?>
					</div>

					<p class="mb-3 text-muted flex-grow-1">
						<?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '...' ) ); ?>
					</p>

					<div class="mt-auto">
						<a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
							<?php esc_html_e( 'Ler mais', 'theme-wordpress' ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</article>
