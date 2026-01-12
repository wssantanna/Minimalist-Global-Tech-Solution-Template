<?php
/**
 * The header template file
 *
 * @package Theme_WordPress
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="visually-hidden-focusable" href="#main-content">
	<?php esc_html_e( 'Pular para o conteúdo', 'theme-wordpress' ); ?>
</a>

<header class="site-header">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-4">
		<div class="container">
			<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					bloginfo( 'name' );
				}
				?>
			</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Alternar navegação', 'theme-wordpress' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarNav">
				<?php
				wp_nav_menu( array(
					'theme_location'  => 'primary',
					'container'       => false,
					'menu_class'      => 'navbar-nav mx-auto',
					'fallback_cb'     => 'theme_wordpress_fallback_menu',
					'walker'          => new Theme_WordPress_Bootstrap_Nav_Walker(),
				) );
				?>

				<div class="d-flex">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</nav>
</header>

<main id="main-content" class="site-main py-5">
