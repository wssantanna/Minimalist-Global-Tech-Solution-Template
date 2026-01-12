<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Customizer;

use ThemeCore\Presentation\Customizer\Section\ColorSection;
use ThemeCore\Presentation\Customizer\Section\LayoutSection;
use ThemeCore\Presentation\Customizer\Section\TypographySection;
use WP_Customize_Manager;

/**
 * Main controller for WordPress Customizer integration
 *
 * Orchestrates the registration of Customizer panels, sections,
 * and settings.
 */
final readonly class CustomizerController
{

    /**
     * Register all Customizer components
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    public function register(WP_Customize_Manager $wpCustomize): void
    {
        $this->removeNativeSections($wpCustomize);
        $this->reorganizePriorities($wpCustomize);
        $this->registerPanel($wpCustomize);
        $this->registerSections($wpCustomize);
    }

    /**
     * Remove native WordPress sections that are not needed
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function removeNativeSections(WP_Customize_Manager $wpCustomize): void
    {
        // Remove Colors section
        $wpCustomize->remove_section('colors');

        // Remove Header Image section
        $wpCustomize->remove_section('header_image');

        // Remove Background Image section
        $wpCustomize->remove_section('background_image');
    }

    /**
     * Reorganize section priorities
     * Order: Identity, Menus, Static Front Page, Theme Settings, Additional CSS
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function reorganizePriorities(WP_Customize_Manager $wpCustomize): void
    {
        // 1. Site Identity (Identidade do site) - Priority 20
        if ($wpCustomize->get_section('title_tagline')) {
            $wpCustomize->get_section('title_tagline')->priority = 20;
        }

        // 2. Menus - Priority 100
        if ($wpCustomize->get_panel('nav_menus')) {
            $wpCustomize->get_panel('nav_menus')->priority = 100;
        }

        // 3. Static Front Page (Configurações da página inicial) - Priority 120
        if ($wpCustomize->get_section('static_front_page')) {
            $wpCustomize->get_section('static_front_page')->priority = 120;
        }

        // 4. Theme Globaltech Settings panel will be 130 (set in registerPanel)

        // 5. Additional CSS (CSS adicional) - Priority 200
        if ($wpCustomize->get_section('custom_css')) {
            $wpCustomize->get_section('custom_css')->priority = 200;
        }
    }

    /**
     * Register the main theme panel
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerPanel(WP_Customize_Manager $wpCustomize): void
    {
        $wpCustomize->add_panel('theme_globaltech', [
            'title' => __('Configurações de aparência', 'theme-globaltech'),
            'description' => __('Customize your theme appearance and behavior', 'theme-globaltech'),
            'priority' => 130,
        ]);
    }

    /**
     * Register all Customizer sections
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    private function registerSections(WP_Customize_Manager $wpCustomize): void
    {
        $colorSection = new ColorSection();
        $colorSection->register($wpCustomize);

        $layoutSection = new LayoutSection();
        $layoutSection->register($wpCustomize);

        $typographySection = new TypographySection();
        $typographySection->register($wpCustomize);
    }
}
