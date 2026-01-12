<?php
/**
 * The template for displaying search results
 *
 * @package Theme_WordPress
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<?php if ( have_posts() ) : ?>
				<header class="page-header mb-4 pb-3 border-bottom">
					<h1 class="page-title">
						<?php
						printf(
							/* translators: %s: search query */
							esc_html__( 'Resultados da busca por: %s', 'theme-wordpress' ),
							'<span class="text-primary">' . get_search_query() . '</span>'
						);
						?>
					</h1>
					<p class="text-muted">
						<?php
						/* translators: %d: number of search results */
						printf(
							esc_html( _n( '%d resultado encontrado', '%d resultados encontrados', $wp_query->found_posts, 'theme-wordpress' ) ),
							number_format_i18n( $wp_query->found_posts )
						);
						?>
					</p>
				</header>

				<?php
				$display_style = get_theme_mod( 'theme_posts_display_style', 'cards' );

				if ( $display_style === 'list' ) :
					?>
					<div class="posts-list">
						<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content', 'list' );
						endwhile;
						?>
					</div>
				<?php else : ?>
					<div class="<?php echo esc_attr( theme_wordpress_get_grid_class() ); ?>">
						<?php
						while ( have_posts() ) :
							the_post();
							?>
							<div class="col">
								<?php get_template_part( 'template-parts/content', 'card' ); ?>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>

				<nav aria-label="<?php esc_attr_e( 'Navegação de posts', 'theme-wordpress' ); ?>" class="mt-5">
					<?php
					the_posts_pagination( array(
						'mid_size'           => 2,
						'prev_text'          => __( '&laquo; Anterior', 'theme-wordpress' ),
						'next_text'          => __( 'Próxima &raquo;', 'theme-wordpress' ),
						'screen_reader_text' => __( 'Navegação de posts', 'theme-wordpress' ),
						'class'              => 'pagination justify-content-center',
					) );
					?>
				</nav>

			<?php else : ?>
				<div class="alert alert-warning" role="alert">
					<h2 class="alert-heading"><?php esc_html_e( 'Nenhum resultado encontrado', 'theme-wordpress' ); ?></h2>
					<p>
						<?php
						printf(
							/* translators: %s: search query */
							esc_html__( 'Desculpe, mas nada foi encontrado para "%s". Tente novamente com palavras-chave diferentes.', 'theme-wordpress' ),
							'<strong>' . get_search_query() . '</strong>'
						);
						?>
					</p>

					<div class="mt-3">
						<?php get_search_form(); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
