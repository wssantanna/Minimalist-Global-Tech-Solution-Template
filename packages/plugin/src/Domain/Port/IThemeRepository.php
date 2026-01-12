<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Port;

use ThemeCore\Domain\Entity\ThemeConfig;

interface IThemeRepository
{
    /**
     * Retrieve the current theme configuration
     */
    public function get(): ThemeConfig;

    /**
     * Save the theme configuration
     */
    public function save(ThemeConfig $config): void;

    /**
     * Check if theme configuration exists
     */
    public function exists(): bool;

    /**
     * Delete the theme configuration (reset to defaults)
     */
    public function delete(): void;
}
