<?php
/**
 * Customizer: Funções Auxiliares para Cores
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calcula a luminância relativa de uma cor
 *
 * @param string $hex_color Cor em hexadecimal (#RRGGBB)
 * @return float Luminância entre 0 e 1
 */
function theme_wordpress_get_relative_luminance( $hex_color ) {
	$hex_color = ltrim( $hex_color, '#' );

	$r = hexdec( substr( $hex_color, 0, 2 ) ) / 255;
	$g = hexdec( substr( $hex_color, 2, 2 ) ) / 255;
	$b = hexdec( substr( $hex_color, 4, 2 ) ) / 255;

	$r = $r <= 0.03928 ? $r / 12.92 : pow( ( $r + 0.055 ) / 1.055, 2.4 );
	$g = $g <= 0.03928 ? $g / 12.92 : pow( ( $g + 0.055 ) / 1.055, 2.4 );
	$b = $b <= 0.03928 ? $b / 12.92 : pow( ( $b + 0.055 ) / 1.055, 2.4 );

	return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

/**
 * Calcula o contraste entre duas cores
 *
 * @param string $color1 Primeira cor em hexadecimal
 * @param string $color2 Segunda cor em hexadecimal
 * @return float Razão de contraste (1 a 21)
 */
function theme_wordpress_get_contrast_ratio( $color1, $color2 ) {
	$l1 = theme_wordpress_get_relative_luminance( $color1 );
	$l2 = theme_wordpress_get_relative_luminance( $color2 );

	$lighter = max( $l1, $l2 );
	$darker  = min( $l1, $l2 );

	return ( $lighter + 0.05 ) / ( $darker + 0.05 );
}

/**
 * Determina a melhor cor de texto (branco ou preto) para uma cor de fundo
 * Segue as diretrizes WCAG 2.1 para contraste AA (mínimo 4.5:1)
 *
 * @param string $bg_color Cor de fundo em hexadecimal
 * @return string '#ffffff' ou '#000000'
 */
function theme_wordpress_get_contrast_color( $bg_color ) {
	$white_contrast = theme_wordpress_get_contrast_ratio( $bg_color, '#ffffff' );
	$black_contrast = theme_wordpress_get_contrast_ratio( $bg_color, '#000000' );

	return $white_contrast > $black_contrast ? '#ffffff' : '#000000';
}

/**
 * Verifica se uma cor é clara ou escura
 *
 * @param string $hex_color Cor em hexadecimal
 * @return bool True se for clara, False se for escura
 */
function theme_wordpress_is_light_color( $hex_color ) {
	$luminance = theme_wordpress_get_relative_luminance( $hex_color );
	return $luminance > 0.5;
}

/**
 * Converte hexadecimal para RGB
 *
 * @param string $hex_color Cor em hexadecimal
 * @return array Array com valores R, G, B (0-255)
 */
function theme_wordpress_hex_to_rgb( $hex_color ) {
	$hex_color = ltrim( $hex_color, '#' );

	return array(
		'r' => hexdec( substr( $hex_color, 0, 2 ) ),
		'g' => hexdec( substr( $hex_color, 2, 2 ) ),
		'b' => hexdec( substr( $hex_color, 4, 2 ) ),
	);
}

/**
 * Gera uma versão mais clara ou escura de uma cor
 *
 * @param string $hex_color Cor em hexadecimal
 * @param int    $percent   Percentual de ajuste (-100 a 100)
 * @return string Cor ajustada em hexadecimal
 */
function theme_wordpress_adjust_brightness( $hex_color, $percent ) {
	$rgb = theme_wordpress_hex_to_rgb( $hex_color );

	$r = max( 0, min( 255, $rgb['r'] + ( $rgb['r'] * $percent / 100 ) ) );
	$g = max( 0, min( 255, $rgb['g'] + ( $rgb['g'] * $percent / 100 ) ) );
	$b = max( 0, min( 255, $rgb['b'] + ( $rgb['b'] * $percent / 100 ) ) );

	return sprintf( '#%02x%02x%02x', $r, $g, $b );
}
