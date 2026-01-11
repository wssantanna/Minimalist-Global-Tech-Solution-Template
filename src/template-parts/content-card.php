<?php
/**
 * Template part for displaying posts in card format (Bootstrap 5)
 *
 * @package Theme_WordPress
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<div class="card h-100 border-0">
		<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ir para %s', 'theme-wordpress' ), get_the_title() ) ); ?>">
				<?php
				the_post_thumbnail( 'theme-card', array(
					'class' => 'card-img-top rounded',
					'alt'   => the_title_attribute( array( 'echo' => false ) ),
				) );
				?>
			</a>
		<?php endif; ?>

		<div class="card-body d-flex flex-column px-0 pt-3">
			<h2 class="card-title h5 fw-bold mb-2">
				<a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark stretched-link">
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

			<p class="card-text text-muted">
				<?php echo esc_html( wp_trim_words( get_the_excerpt(), 12, '...' ) ); ?>
			</p>
		</div>
	</div>
</article>
