<?php

declare(strict_types=1);

namespace ThemeCore\Presentation\Hook;

/**
 * Registry for WordPress hooks
 *
 * Centralizes all hook registrations for better organization
 * and testability.
 */
final class HookRegistry
{
    /** @var array<HookInterface> */
    private array $hooks = [];

    /**
     * Add a hook to the registry
     *
     * @param HookInterface $hook
     * @return self
     */
    public function add(HookInterface $hook): self
    {
        $this->hooks[] = $hook;
        return $this;
    }

    /**
     * Register all hooks with WordPress
     *
     * This should be called during plugin initialization.
     *
     * @return void
     */
    public function registerAll(): void
    {
        foreach ($this->hooks as $hook) {
            $hook->register();
        }
    }

    /**
     * Get all registered hooks
     *
     * @return array<HookInterface>
     */
    public function getHooks(): array
    {
        return $this->hooks;
    }

    /**
     * Clear all hooks (useful for testing)
     *
     * @return void
     */
    public function clear(): void
    {
        $this->hooks = [];
    }
}
