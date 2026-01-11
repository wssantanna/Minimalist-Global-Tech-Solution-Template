<?php
/**
 * WordPress Customizer - Arquivo Principal
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_wordpress_load_customizer_modules() {
	$customizer_dir = get_template_directory() . '/inc/customizer/';

	require_once $customizer_dir . 'customizer-color-helpers.php';
	require_once $customizer_dir . 'customizer-logo.php';
	require_once $customizer_dir . 'customizer-colors.php';
	require_once $customizer_dir . 'customizer-layout.php';
	require_once $customizer_dir . 'customizer-typography.php';
	require_once $customizer_dir . 'customizer-css.php';
}
add_action( 'after_setup_theme', 'theme_wordpress_load_customizer_modules' );

function theme_wordpress_customize_register( $wp_customize ) {

	$wp_customize->add_panel( 'theme_wordpress_panel', array(
		'title'       => __( 'Opções do Tema', 'theme-wordpress' ),
		'description' => __( 'Personalize a aparência e o comportamento do seu tema', 'theme-wordpress' ),
		'priority'    => 30,
	) );

	$wp_customize->add_panel( 'theme_wordpress_homepage_panel', array(
		'title'       => __( 'Layout', 'theme-wordpress' ),
		'description' => __( 'Personalize o layout, exibição e conteúdo da página inicial', 'theme-wordpress' ),
		'priority'    => 35,
	) );

	$wp_customize->add_section( 'theme_wordpress_identity', array(
		'title'    => __( ' Visual', 'theme-wordpress' ),
		'panel'    => 'theme_wordpress_panel',
		'priority' => 10,
	) );

	// 2. Exibição
	$wp_customize->add_section( 'theme_wordpress_content', array(
		'title'    => __( 'Exibição', 'theme-wordpress' ),
		'priority' => 20,
	) );

	// 4. Cores
	$wp_customize->add_section( 'theme_wordpress_colors', array(
		'title'    => __( 'Cores', 'theme-wordpress' ),
		'priority' => 40,
	) );

	// 5. Tipografia
	$wp_customize->add_section( 'theme_wordpress_typography', array(
		'title'    => __( 'Tipografia', 'theme-wordpress' ),
		'priority' => 50,
	) );
}
add_action( 'customize_register', 'theme_wordpress_customize_register', 5 );

/**
 * Ajusta a ordem das seções nativas do WordPress no Customizer
 */
function theme_wordpress_customize_sections_order( $wp_customize ) {
	// 1. Configurações da página inicial
	if ( $wp_customize->get_section( 'static_front_page' ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 10;
	}

	// 3. Logo (title_tagline)
	if ( $wp_customize->get_section( 'title_tagline' ) ) {
		$wp_customize->get_section( 'title_tagline' )->priority = 30;
	}

	// 6. Menus (nativa do WordPress)
	if ( $wp_customize->get_panel( 'nav_menus' ) ) {
		$wp_customize->get_panel( 'nav_menus' )->priority = 60;
	}

	// 7. CSS adicional (nativa do WordPress)
	if ( $wp_customize->get_section( 'custom_css' ) ) {
		$wp_customize->get_section( 'custom_css' )->priority = 70;
	}
}
add_action( 'customize_register', 'theme_wordpress_customize_sections_order', 999 );
