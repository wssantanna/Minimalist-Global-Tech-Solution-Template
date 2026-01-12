<?php
/**
 * Theme WordPress Functions
 *
 * @package Theme_WordPress
 * @version 2.0.0
 *
 * IMPORTANT: This theme now requires the "Theme Core Features" plugin (v2.0.0+)
 * The plugin handles theme setup, customizer, and asset management using hexagonal architecture.
 *
 * This file now contains only theme-specific template functions.
 * Core functionality has been moved to: wp-content/plugins/theme-core-features/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ============================================================================
// PLUGIN DEPENDENCY CHECK
// ============================================================================

/**
 * Check if Theme Core Features plugin is active
 *
 * @return bool
 */
function theme_wordpress_check_plugin_dependency() {
	// Check if plugin is loaded by looking for its constant
	if ( ! defined( 'THEME_CORE_VERSION' ) ) {
		add_action( 'admin_notices', 'theme_wordpress_plugin_dependency_notice' );
		return false;
	}
	return true;
}
add_action( 'after_setup_theme', 'theme_wordpress_check_plugin_dependency', 1 );

/**
 * Display admin notice if plugin is not active
 *
 * @return void
 */
function theme_wordpress_plugin_dependency_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	$message = sprintf(
		/* translators: 1: Plugin name, 2: Minimum plugin version */
		__( 'This theme requires the %1$s plugin (v%2$s or higher) to be installed and activated.', 'theme-wordpress' ),
		'<strong>Theme Core Features</strong>',
		'2.0.0'
	);

	printf(
		'<div class="notice notice-error"><p>%s <a href="%s">%s</a></p></div>',
		$message,
		esc_url( admin_url( 'plugins.php' ) ),
		__( 'Go to Plugins', 'theme-wordpress' )
	);
}

// ============================================================================
// LEGACY FALLBACK - Only used if plugin is NOT active
// ============================================================================
// NOTE: The Theme Core Features plugin (v2.0.0+) now handles:
// - Theme setup (ThemeSetupHook): text domain, theme supports, custom logo
// - Navigation menus registration
// - Customizer sections and controls
// - Assets enqueuing (style.css via AssetsHook)
//
// This fallback ensures basic functionality if the plugin is deactivated.

// ============================================================================
// THEME-SPECIFIC FUNCTIONS (not handled by plugin)
// ============================================================================

/**
 * Disable comments and trackbacks
 *
 * @return void
 */
function theme_wordpress_disable_comments() {
	remove_post_type_support( 'post', 'comments' );
	remove_post_type_support( 'post', 'trackbacks' );
}
add_action( 'init', 'theme_wordpress_disable_comments' );

/**
 * Enqueue theme-specific scripts and styles
 * NOTE: Main style.css is handled by the plugin (AssetsHook)
 *
 * @return void
 */
function theme_wordpress_enqueue_scripts() {
	// Enqueue Bootstrap CSS (required by theme templates)
	wp_enqueue_style(
		'bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
		array(),
		'5.3.2'
	);

	// Enqueue Bootstrap JavaScript
	wp_enqueue_script(
		'bootstrap-bundle',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.2',
		true
	);

	// Enqueue theme-specific JavaScript
	wp_enqueue_script(
		'theme-wordpress-main',
		get_template_directory_uri() . '/main.js',
		array( 'bootstrap-bundle' ),
		filemtime( get_template_directory() . '/main.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'theme_wordpress_enqueue_scripts' );

// ============================================================================
// BOOTSTRAP NAVIGATION WALKER (theme-specific)
// ============================================================================

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

/**
 * Get Bootstrap grid classes based on customizer settings
 *
 * @return string Grid classes
 */
function theme_wordpress_get_grid_class() {
	$columns = get_theme_mod( 'theme_posts_layout', '3' );
	return 'row row-cols-1 row-cols-md-2 row-cols-lg-' . esc_attr( $columns ) . ' g-4';
}

