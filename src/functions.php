<?php
/**
 * Theme WordPress Functions
 *
 * @package Theme_WordPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/customizer/customizer.php';

function theme_wordpress_setup() {
	load_theme_textdomain( 'theme-wordpress', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'gallery',
		'caption',
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 50,
		'width'       => 150,
		'flex-height' => true,
		'flex-width'  => true,
	) );
}
add_action( 'after_setup_theme', 'theme_wordpress_setup' );

function theme_wordpress_disable_comments() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );
}
add_action( 'init', 'theme_wordpress_disable_comments' );

function theme_wordpress_register_menus() {
	register_nav_menus( array(
		'primary_menu' => __( 'Menu Principal', 'theme-wordpress' ),
	) );
}
add_action( 'after_setup_theme', 'theme_wordpress_register_menus' );


function theme_wordpress_enqueue_scripts() {
	wp_enqueue_style(
		'bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
		array(),
		'5.3.2'
	);

	wp_enqueue_style(
		'theme-wordpress-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
	
	wp_enqueue_script(
		'bootstrap-bundle',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.2',
		true
	);

	wp_enqueue_script(
		'theme-wordpress-main',
		get_template_directory_uri() . '/main.js',
		array( 'bootstrap-bundle' ),
		filemtime( get_template_directory() . '/main.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'theme_wordpress_enqueue_scripts' );

class Theme_WordPress_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'nav-item';

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$classes[] = 'dropdown';
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= $indent . '<li' . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts['class'] = 'nav-link';

		if ( in_array( 'current-menu-item', $classes ) ) {
			$atts['class'] .= ' active';
		}

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$atts['class']           .= ' dropdown-toggle';
			$atts['data-bs-toggle']   = 'dropdown';
			$atts['aria-expanded']    = 'false';
			$atts['role']             = 'button';
		}

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

function theme_wordpress_fallback_menu() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<div class="alert alert-warning m-0">';
		printf(
			'<small>%s <a href="%s" class="alert-link">%s</a></small>',
			esc_html__( 'Menu não configurado.', 'theme-wordpress' ),
			esc_url( admin_url( 'nav-menus.php' ) ),
			esc_html__( 'Configure agora', 'theme-wordpress' )
		);
		echo '</div>';
	}

	echo '<ul class="navbar-nav ms-auto">';
	wp_list_pages( array(
		'title_li' => '',
		'depth'    => 1,
	) );
	echo '</ul>';
}


function theme_wordpress_custom_image_sizes() {
	add_image_size( 'theme-card', 800, 450, true );
}
add_action( 'after_setup_theme', 'theme_wordpress_custom_image_sizes' );

function theme_wordpress_custom_image_sizes_names( $sizes ) {
	return array_merge( $sizes, array(
		'theme-card' => __( 'Card de Post', 'theme-wordpress' ),
	) );
}
add_filter( 'image_size_names_choose', 'theme_wordpress_custom_image_sizes_names' );

function theme_wordpress_get_grid_class() {
	$columns = get_theme_mod( 'theme_posts_layout', '3' );
	return 'row row-cols-1 row-cols-md-2 row-cols-lg-' . esc_attr( $columns ) . ' g-4';
}
