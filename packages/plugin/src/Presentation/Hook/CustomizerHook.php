<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Hook;

use ThemeCore\Presentation\Customizer\CustomizerController;
use WP_Customize_Manager;

/**
 * Hook for registering WordPress Customizer
 *
 * Connects the CustomizerController to WordPress customize_register action.
 */
final readonly class CustomizerHook implements HookInterface
{
    public function __construct(
        private CustomizerController $controller,
    ) {
    }

    /**
     * Register the hook with WordPress
     *
     * @return void
     */
    public function register(): void
    {
        add_action('customize_register', [$this, 'registerCustomizer']);
    }

    /**
     * Register Customizer components
     *
     * @param WP_Customize_Manager $wpCustomize
     * @return void
     */
    public function registerCustomizer(WP_Customize_Manager $wpCustomize): void
    {
        $this->controller->register($wpCustomize);
    }
}
