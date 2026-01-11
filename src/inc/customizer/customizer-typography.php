<?php
/**
 * Customizer: Controles de Tipografia
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_wordpress_customize_typography( $wp_customize ) {

	$google_fonts = array(
		''              => __( 'Padrão do Sistema', 'theme-wordpress' ),
		'Roboto'        => 'Roboto',
		'Open Sans'     => 'Open Sans',
		'Lato'          => 'Lato',
		'Montserrat'    => 'Montserrat',
		'Ubuntu'        => 'Ubuntu',
		'Raleway'       => 'Raleway',
		'Inter'         => 'Inter',
	);

	$wp_customize->add_setting( 'theme_body_font', array(
		'default'           => '',
		'sanitize_callback' => 'theme_wordpress_sanitize_font_choice',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'theme_body_font', array(
		'type'        => 'select',
		'label'       => __( 'Fonte do Corpo do Texto', 'theme-wordpress' ),
		'description' => __( 'Escolha a fonte para parágrafos e textos gerais', 'theme-wordpress' ),
		'section'     => 'theme_wordpress_typography',
		'priority'    => 10,
		'choices'     => $google_fonts,
	) );

	$wp_customize->add_setting( 'theme_heading_font', array(
		'default'           => '',
		'sanitize_callback' => 'theme_wordpress_sanitize_font_choice',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'theme_heading_font', array(
		'type'        => 'select',
		'label'       => __( 'Fonte dos Títulos', 'theme-wordpress' ),
		'description' => __( 'Fonte para h1, h2, h3, h4, h5, h6 e display', 'theme-wordpress' ),
		'section'     => 'theme_wordpress_typography',
		'priority'    => 20,
		'choices'     => $google_fonts,
	) );

}
add_action( 'customize_register', 'theme_wordpress_customize_typography', 10 );

function theme_wordpress_sanitize_font_choice( $input ) {
	$valid = array(
		'',
		'Roboto',
		'Open Sans',
		'Lato',
		'Montserrat',
		'Ubuntu',
		'Raleway',
		'Inter',
	);
	return in_array( $input, $valid, true ) ? $input : '';
}

function theme_wordpress_enqueue_google_fonts() {
	$body_font    = get_theme_mod( 'theme_body_font', '' );
	$heading_font = get_theme_mod( 'theme_heading_font', '' );

	$fonts_to_load = array();

	if ( ! empty( $body_font ) ) {
		$fonts_to_load[] = str_replace( ' ', '+', $body_font ) . ':ital,wght@0,400;0,700;1,400;1,700';
	}

	if ( ! empty( $heading_font ) && $heading_font !== $body_font ) {
		$fonts_to_load[] = str_replace( ' ', '+', $heading_font ) . ':wght@400;700';
	}

	if ( ! empty( $fonts_to_load ) ) {
		$fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $fonts_to_load ) . '&display=swap';
		wp_enqueue_style( 'theme-google-fonts', $fonts_url, array(), null );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_wordpress_enqueue_google_fonts' );
