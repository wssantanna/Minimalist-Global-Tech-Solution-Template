<?php
/**
 * The footer template file
 *
 * @package Theme_WordPress
 */
?>
</main><!-- #main-content -->

<footer class="site-footer bg-light border-top mt-5">
	<div class="container">
		<div class="row py-3">
			<div class="col-12">
				<p class="text-center text-muted mb-0">
					<small>
						<?php
						printf(
							'&copy; %s %s. %s.',
							esc_html( date( 'Y' ) ),
							esc_html( get_bloginfo( 'name' ) ),
							esc_html__( 'Todos os direitos reservados', 'theme-wordpress' )
						);
						?>
					</small>
				</p>

				<?php
				$footer_text = get_theme_mod( 'theme_footer_text', '' );
				if ( ! empty( $footer_text ) ) :
					?>
					<div class="text-center mt-2">
						<small class="text-muted">
							<?php echo wp_kses_post( $footer_text ); ?>
						</small>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
