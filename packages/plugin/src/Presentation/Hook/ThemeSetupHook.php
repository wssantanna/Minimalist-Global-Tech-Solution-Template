<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Hook;

/**
 * Theme setup hook
 *
 * Registers WordPress theme supports, text domain, and other
 * theme initialization tasks.
 */
final readonly class ThemeSetupHook implements HookInterface
{

    /**
     * Register the hook with WordPress
     *
     * @return void
     */
    public function register(): void
    {
        add_action('after_setup_theme', [$this, 'setup']);
    }

    /**
     * Setup theme supports and features
     *
     * @return void
     */
    public function setup(): void
    {
        // Load text domain
        load_theme_textdomain('theme-globaltech', get_template_directory() . '/languages');

        // Add theme support for title tag
        add_theme_support('title-tag');

        // Add theme support for post thumbnails
        add_theme_support('post-thumbnails');

        // Add theme support for HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        // Add theme support for custom logo
        add_theme_support('custom-logo', [
            'height' => 50,
            'width' => 150,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => ['site-title', 'site-description'],
            'unlink-homepage-logo' => true,
        ]);

        // Add theme support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Register navigation menus
        register_nav_menus([
            'primary' => __('Primary Menu', 'theme-globaltech'),
            'footer' => __('Footer Menu', 'theme-globaltech'),
        ]);
    }
}
