<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Port;

use ThemeCore\Domain\Entity\ThemeConfig;

interface ICSSGenerator
{
    /**
     * Generate CSS from theme configuration
     */
    public function generate(ThemeConfig $config): string;

    /**
     * Generate minified CSS
     */
    public function generateMinified(ThemeConfig $config): string;

    /**
     * Generate CSS variables (custom properties)
     */
    public function generateCssVariables(ThemeConfig $config): string;
}
