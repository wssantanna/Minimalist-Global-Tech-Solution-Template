<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Hook;

/**
 * Interface for WordPress hook registrations
 *
 * All hooks (actions and filters) must implement this interface
 * to be managed by the HookRegistry.
 */
interface HookInterface
{
    /**
     * Register the hook with WordPress
     *
     * This method should call add_action() or add_filter()
     * to register the hook with WordPress.
     *
     * @return void
     */
    public function register(): void;
}
