<?php
/**
 * Customizer: Controles de Cores Simplificados
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_wordpress_customize_colors( $wp_customize ) {

	$wp_customize->add_setting( 'theme_primary_color', array(
		'default'           => '#0d6efd',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_primary_color', array(
		'label'       => __( 'Cor primária', 'theme-wordpress' ),
		'description' => __( 'Cor primária do tema usada em links, botões e destaques', 'theme-wordpress' ),
		'section'     => 'theme_wordpress_colors',
		'priority'    => 10,
	) ) );

	$wp_customize->add_setting( 'theme_header_bg_color', array(
		'default'           => '#212529',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_header_bg_color', array(
		'label'       => __( 'Cor do cabeçalho', 'theme-wordpress' ),
		'description' => __( 'Cor de fundo do cabeçalho (texto será ajustado automaticamente)', 'theme-wordpress' ),
		'section'     => 'theme_wordpress_colors',
		'priority'    => 20,
	) ) );

	$wp_customize->add_setting( 'theme_footer_bg_color', array(
		'default'           => '#f8f9fa',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_footer_bg_color', array(
		'label'       => __( 'Cor do rodapé', 'theme-wordpress' ),
		'description' => __( 'Cor de fundo do rodapé (texto será ajustado automaticamente)', 'theme-wordpress' ),
		'section'     => 'theme_wordpress_colors',
		'priority'    => 30,
	) ) );

	// Selective Refresh para preview em tempo real
	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'theme_primary_color', array(
			'selector'            => ':root',
			'container_inclusive' => false,
			'render_callback'     => '__return_false',
		) );
	}
}
add_action( 'customize_register', 'theme_wordpress_customize_colors', 10 );
