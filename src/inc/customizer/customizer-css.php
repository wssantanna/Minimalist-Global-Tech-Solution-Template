<?php
/**
 * Customizer: Gerador de CSS Dinâmico com CSS Variables
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_wordpress_customizer_css() {
	// Cores do usuário
	$primary_color       = get_theme_mod( 'theme_primary_color', '#0d6efd' );
	$header_bg           = get_theme_mod( 'theme_header_bg_color', '#212529' );
	$footer_bg           = get_theme_mod( 'theme_footer_bg_color', '#f8f9fa' );

	// Cores calculadas automaticamente para melhor contraste
	$header_text = theme_wordpress_get_contrast_color( $header_bg );
	$footer_text = theme_wordpress_get_contrast_color( $footer_bg );

	// Configurações adicionais
	$container_layout    = get_theme_mod( 'theme_container_layout', 'full' );
	$container_max_width = get_theme_mod( 'theme_container_max_width', 1320 );
	$logo_width          = get_theme_mod( 'theme_logo_width', 150 );
	$logo_height         = get_theme_mod( 'theme_logo_height', 50 );
	$body_font           = get_theme_mod( 'theme_body_font', '' );
	$heading_font        = get_theme_mod( 'theme_heading_font', '' );

	// Conversão para RGB
	$primary_rgb = implode( ',', sscanf( $primary_color, "#%02x%02x%02x" ) );

	$custom_css = ':root {
	--theme-primary: ' . esc_attr( $primary_color ) . ';
	--theme-primary-rgb: ' . esc_attr( $primary_rgb ) . ';
	--theme-primary-hover: rgba(' . esc_attr( $primary_rgb ) . ', 0.85);
	--theme-primary-light: rgba(' . esc_attr( $primary_rgb ) . ', 0.1);
	--theme-header-bg: ' . esc_attr( $header_bg ) . ';
	--theme-header-text: ' . esc_attr( $header_text ) . ';
	--theme-footer-bg: ' . esc_attr( $footer_bg ) . ';
	--theme-footer-text: ' . esc_attr( $footer_text ) . ';
	--theme-container-max-width: ' . esc_attr( $container_max_width ) . 'px;
	--theme-logo-width: ' . esc_attr( $logo_width ) . 'px;
	--theme-logo-height: ' . esc_attr( $logo_height ) . 'px;';

	if ( ! empty( $body_font ) ) {
		$custom_css .= '
	--bs-body-font-family: "' . esc_attr( $body_font ) . '", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;';
	}

	if ( ! empty( $heading_font ) ) {
		$custom_css .= '
	--theme-heading-font-family: "' . esc_attr( $heading_font ) . '", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;';
	}

	$custom_css .= '
}
';

	if ( ! empty( $heading_font ) ) {
		$custom_css .= '
h1, h2, h3, h4, h5, h6,
.h1, .h2, .h3, .h4, .h5, .h6,
.display-1, .display-2, .display-3, .display-4, .display-5, .display-6 {
	font-family: var(--theme-heading-font-family) !important;
}
';
	}

	$custom_css .= '
.bg-primary,
.badge.bg-primary,
.btn-primary {
	background-color: var(--theme-primary) !important;
	border-color: var(--theme-primary) !important;
}

.btn-primary:hover,
.btn-primary:focus {
	background-color: var(--theme-primary-hover) !important;
	border-color: var(--theme-primary-hover) !important;
}

.text-primary,
a.text-primary:hover {
	color: var(--theme-primary) !important;
}

a {
	color: var(--theme-primary);
}

a:hover {
	color: var(--theme-primary-hover);
}

.alert-primary {
	background-color: var(--theme-primary-light) !important;
	border-color: var(--theme-primary) !important;
	color: var(--theme-primary) !important;
}

.navbar.bg-dark,
.site-header .navbar {
	background-color: var(--theme-header-bg) !important;
	color: var(--theme-header-text) !important;
}

.navbar.bg-dark .navbar-brand,
.navbar.bg-dark .nav-link {
	color: var(--theme-header-text) !important;
}

.navbar.bg-dark .nav-link:hover,
.navbar.bg-dark .nav-link:focus {
	color: rgba(var(--theme-primary-rgb), 1) !important;
}

.site-footer.bg-light,
.site-footer {
	background-color: var(--theme-footer-bg) !important;
	color: var(--theme-footer-text) !important;
}

.custom-logo {
	width: var(--theme-logo-width);
	height: var(--theme-logo-height);
	object-fit: contain;
}
';

	if ( 'boxed' === $container_layout ) {
		$custom_css .= '
body {
	background-color: #e9ecef;
}

.site-main,
.site-header,
.site-footer {
	max-width: var(--theme-container-max-width);
	margin-left: auto;
	margin-right: auto;
}

.site-main {
	background-color: #ffffff;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}
';
	}

	wp_add_inline_style( 'bootstrap', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'theme_wordpress_customizer_css', 20 );

function theme_wordpress_customizer_live_preview() {
	wp_enqueue_script(
		'theme-wordpress-customizer-preview',
		get_template_directory_uri() . '/inc/customizer/customizer-preview.js',
		array( 'customize-preview' ),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'customize_preview_init', 'theme_wordpress_customizer_live_preview' );
