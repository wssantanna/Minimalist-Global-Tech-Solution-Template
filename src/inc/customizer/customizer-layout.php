<?php
/**
 * Customizer: Controles de Layout
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_wordpress_customize_layout( $wp_customize ) {

	$wp_customize->add_setting( 'theme_posts_display_style', array(
		'default'           => 'cards',
		'sanitize_callback' => 'theme_wordpress_sanitize_display_style',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'theme_posts_display_style', array(
		'type'        => 'radio',
		'label'       => __( 'Estilo de Exibição dos Posts', 'theme-wordpress' ),
		'description' => __( 'Escolha como os posts serão exibidos na listagem', 'theme-wordpress' ),
		'section'     => 'theme_wordpress_content',
		'priority'    => 5,
		'choices'     => array(
			'cards' => __( 'Grade', 'theme-wordpress' ),
			'list'  => __( 'Lista', 'theme-wordpress' ),
		),
	) );

	$wp_customize->add_setting( 'theme_posts_layout', array(
		'default'           => '3',
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'theme_posts_layout', array(
		'type'            => 'select',
		'label'           => __( 'Colunas de Posts', 'theme-wordpress' ),
		'section'         => 'theme_wordpress_content',
		'priority'        => 10,
		'active_callback' => 'theme_wordpress_is_cards_layout',
		'choices'         => array(
			'2' => __( '2 Colunas', 'theme-wordpress' ),
			'3' => __( '3 Colunas', 'theme-wordpress' ),
			'4' => __( '4 Colunas', 'theme-wordpress' ),
		),
	) );

	$wp_customize->add_setting( 'theme_show_post_date', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'theme_show_post_date', array(
		'type'    => 'checkbox',
		'label'   => __( 'Exibir data nos cards de posts', 'theme-wordpress' ),
		'section' => 'theme_wordpress_content',
	) );

	$wp_customize->add_setting( 'theme_show_post_author', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'theme_show_post_author', array(
		'type'    => 'checkbox',
		'label'   => __( 'Exibir autor nos cards de posts', 'theme-wordpress' ),
		'section' => 'theme_wordpress_content',
	) );

}
add_action( 'customize_register', 'theme_wordpress_customize_layout', 10 );

function theme_wordpress_sanitize_display_style( $input ) {
	$valid = array( 'cards', 'list' );
	return in_array( $input, $valid, true ) ? $input : 'cards';
}

function theme_wordpress_is_cards_layout() {
	return get_theme_mod( 'theme_posts_display_style', 'cards' ) === 'cards';
}
