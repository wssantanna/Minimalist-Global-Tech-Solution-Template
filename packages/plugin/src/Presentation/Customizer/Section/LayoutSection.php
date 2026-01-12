<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Customizer\Section;

use WP_Customize_Manager;

/**
 * Layout settings section for WordPress Customizer
 *
 * Manages layout-related settings including layout mode,
 * column count, and sidebar visibility.
 */
final readonly class LayoutSection
{

    /**
     * Register layout section and settings
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    public function register(WP_Customize_Manager $wpCustomize): void
    {
        $this->registerSection($wpCustomize);
        $this->registerSettings($wpCustomize);
        $this->registerControls($wpCustomize);
    }

    /**
     * Register the layout section
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSection(WP_Customize_Manager $wpCustomize): void
    {
        $wpCustomize->add_section('theme_layout', [
            'title' => __('Layout', 'theme-globaltech'),
            'description' => __('Customize theme layout settings', 'theme-globaltech'),
            'panel' => 'theme_globaltech',
            'priority' => 20,
        ]);
    }

    /**
     * Register all layout settings
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSettings(WP_Customize_Manager $wpCustomize): void
    {
        // Layout Mode
        $wpCustomize->add_setting('theme_layout_mode', [
            'default' => 'grid',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => [$this, 'sanitizeLayoutMode'],
        ]);

        // Column Count
        $wpCustomize->add_setting('theme_column_count', [
            'default' => '3',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => [$this, 'sanitizeColumnCount'],
        ]);

        // Show Sidebar
        $wpCustomize->add_setting('theme_show_sidebar', [
            'default' => '1',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => [$this, 'sanitizeCheckbox'],
        ]);
    }

    /**
     * Register all layout controls
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerControls(WP_Customize_Manager $wpCustomize): void
    {
        // Layout Mode Control
        $wpCustomize->add_control('theme_layout_mode', [
            'label' => __('Layout Mode', 'theme-globaltech'),
            'description' => __('Choose how content is displayed', 'theme-globaltech'),
            'section' => 'theme_layout',
            'type' => 'select',
            'choices' => [
                'grid' => __('Grid', 'theme-globaltech'),
                'list' => __('List', 'theme-globaltech'),
                'masonry' => __('Masonry', 'theme-globaltech'),
            ],
            'priority' => 10,
        ]);

        // Column Count Control
        $wpCustomize->add_control('theme_column_count', [
            'label' => __('Column Count', 'theme-globaltech'),
            'description' => __('Number of columns in grid layout', 'theme-globaltech'),
            'section' => 'theme_layout',
            'type' => 'select',
            'choices' => [
                '2' => __('2 Columns', 'theme-globaltech'),
                '3' => __('3 Columns', 'theme-globaltech'),
                '4' => __('4 Columns', 'theme-globaltech'),
            ],
            'priority' => 20,
        ]);

        // Show Sidebar Control
        $wpCustomize->add_control('theme_show_sidebar', [
            'label' => __('Show Sidebar', 'theme-globaltech'),
            'description' => __('Display sidebar on pages', 'theme-globaltech'),
            'section' => 'theme_layout',
            'type' => 'checkbox',
            'priority' => 30,
        ]);
    }

    /**
     * Sanitize layout mode value
     *
     * @param string $value
     * @return string
     */
    public function sanitizeLayoutMode(string $value): string
    {
        $allowed = ['grid', 'list', 'masonry'];
        return in_array($value, $allowed, true) ? $value : 'grid';
    }

    /**
     * Sanitize column count value
     *
     * @param mixed $value
     * @return int
     */
    public function sanitizeColumnCount(mixed $value): int
    {
        $count = (int) $value;
        return ($count >= 2 && $count <= 4) ? $count : 3;
    }

    /**
     * Sanitize checkbox value
     *
     * @param mixed $value
     * @return bool
     */
    public function sanitizeCheckbox(mixed $value): bool
    {
        return (bool) $value;
    }
}
