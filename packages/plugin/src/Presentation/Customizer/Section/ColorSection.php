<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Customizer\Section;

use WP_Customize_Manager;

/**
 * Color settings section for WordPress Customizer
 *
 * Manages all color-related settings including primary, secondary,
 * background, and text colors.
 */
final readonly class ColorSection
{

    /**
     * Register color section and settings
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
     * Register the color section
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSection(WP_Customize_Manager $wpCustomize): void
    {
        $wpCustomize->add_section('theme_colors', [
            'title' => __('Colors', 'theme-globaltech'),
            'description' => __('Customize theme colors', 'theme-globaltech'),
            'panel' => 'theme_globaltech',
            'priority' => 10,
        ]);
    }

    /**
     * Register all color settings
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSettings(WP_Customize_Manager $wpCustomize): void
    {
        // Primary Color
        $wpCustomize->add_setting('theme_primary_color', [
            'default' => '#0D6EFD',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Secondary Color
        $wpCustomize->add_setting('theme_secondary_color', [
            'default' => '#6C757D',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Background Color
        $wpCustomize->add_setting('theme_background_color', [
            'default' => '#FFFFFF',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);

        // Text Color
        $wpCustomize->add_setting('theme_text_color', [
            'default' => '#212529',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ]);
    }

    /**
     * Register all color controls
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerControls(WP_Customize_Manager $wpCustomize): void
    {
        // Primary Color Control
        $wpCustomize->add_control(
            new \WP_Customize_Color_Control(
                $wpCustomize,
                'theme_primary_color',
                [
                    'label' => __('Primary Color', 'theme-globaltech'),
                    'description' => __('Main brand color used for buttons and links', 'theme-globaltech'),
                    'section' => 'theme_colors',
                    'priority' => 10,
                ]
            )
        );

        // Secondary Color Control
        $wpCustomize->add_control(
            new \WP_Customize_Color_Control(
                $wpCustomize,
                'theme_secondary_color',
                [
                    'label' => __('Secondary Color', 'theme-globaltech'),
                    'description' => __('Secondary accent color', 'theme-globaltech'),
                    'section' => 'theme_colors',
                    'priority' => 20,
                ]
            )
        );

        // Background Color Control
        $wpCustomize->add_control(
            new \WP_Customize_Color_Control(
                $wpCustomize,
                'theme_background_color',
                [
                    'label' => __('Background Color', 'theme-globaltech'),
                    'description' => __('Main background color', 'theme-globaltech'),
                    'section' => 'theme_colors',
                    'priority' => 30,
                ]
            )
        );

        // Text Color Control
        $wpCustomize->add_control(
            new \WP_Customize_Color_Control(
                $wpCustomize,
                'theme_text_color',
                [
                    'label' => __('Text Color', 'theme-globaltech'),
                    'description' => __('Main text color', 'theme-globaltech'),
                    'section' => 'theme_colors',
                    'priority' => 40,
                ]
            )
        );
    }
}
