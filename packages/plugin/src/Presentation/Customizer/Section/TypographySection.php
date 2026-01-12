<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Customizer\Section;

use WP_Customize_Manager;

/**
 * Typography settings section for WordPress Customizer
 *
 * Manages font family and typography-related settings.
 */
final readonly class TypographySection
{

    /**
     * Register typography section and settings
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
     * Register the typography section
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSection(WP_Customize_Manager $wpCustomize): void
    {
        $wpCustomize->add_section('theme_typography', [
            'title' => __('Typography', 'theme-globaltech'),
            'description' => __('Customize theme fonts', 'theme-globaltech'),
            'panel' => 'theme_globaltech',
            'priority' => 30,
        ]);
    }

    /**
     * Register all typography settings
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSettings(WP_Customize_Manager $wpCustomize): void
    {
        // Font Family
        $wpCustomize->add_setting('theme_font_family', [
            'default' => 'Open Sans',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'transport' => 'refresh',
            'sanitize_callback' => [$this, 'sanitizeFontFamily'],
        ]);
    }

    /**
     * Register all typography controls
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerControls(WP_Customize_Manager $wpCustomize): void
    {
        // Font Family Control
        $wpCustomize->add_control('theme_font_family', [
            'label' => __('Font Family', 'theme-globaltech'),
            'description' => __('Choose the main font for your theme', 'theme-globaltech'),
            'section' => 'theme_typography',
            'type' => 'select',
            'choices' => $this->getFontChoices(),
            'priority' => 10,
        ]);
    }

    /**
     * Get available font choices
     *
     * @return array<string, string>
     */
    private function getFontChoices(): array
    {
        return [
            'Open Sans' => 'Open Sans',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Poppins' => 'Poppins',
            'Inter' => 'Inter',
            'Arial' => 'Arial',
            'Helvetica' => 'Helvetica',
            'Georgia' => 'Georgia',
            'Times New Roman' => 'Times New Roman',
        ];
    }

    /**
     * Sanitize font family value
     *
     * @param string $value
     * @return string
     */
    public function sanitizeFontFamily(string $value): string
    {
        $allowed = array_keys($this->getFontChoices());
        return in_array($value, $allowed, true) ? $value : 'Open Sans';
    }
}
