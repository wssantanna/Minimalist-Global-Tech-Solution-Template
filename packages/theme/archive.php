<?php
/**
 * The template for displaying archive pages
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
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description text-muted">', '</div>' );
					?>
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
					<h2 class="alert-heading"><?php esc_html_e( 'Nenhum post encontrado', 'theme-wordpress' ); ?></h2>
					<p><?php esc_html_e( 'Desculpe, mas nada foi encontrado neste arquivo.', 'theme-wordpress' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
get_footer();
