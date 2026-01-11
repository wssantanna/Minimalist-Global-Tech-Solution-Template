<?php
/**
 * Customizer: Controles de Logo
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function theme_wordpress_customize_logo( $wp_customize ) {

	$wp_customize->get_section( 'title_tagline' )->title = __( 'Logo', 'theme-wordpress' );
}
add_action( 'customize_register', 'theme_wordpress_customize_logo', 10 );
