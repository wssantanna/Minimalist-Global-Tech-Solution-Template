<?php

declare(strict_types=1);

namespace ThemeCore\Infrastructure\Adapter;

use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Port\ICSSGenerator;

final class WPCSSGenerator implements ICSSGenerator
{
    public function generate(ThemeConfig $config): string
    {
        $css = $this->generateCssVariables($config);
        $css .= "\n\n" . $this->generateUtilityClasses($config);
        $css .= "\n\n" . $this->generateLayoutClasses($config);

        return $css;
    }

    public function generateMinified(ThemeConfig $config): string
    {
        $css = $this->generate($config);

        // Remove comments
        $css = (string) preg_replace('!/\*.*?\*/!s', '', $css);

        // Remove whitespace
        $css = (string) preg_replace('/\s+/', ' ', $css);
        $css = (string) preg_replace('/\s*([{}:;,])\s*/', '$1', $css);

        return trim($css);
    }

    public function generateCssVariables(ThemeConfig $config): string
    {
        $colors = $config->colorScheme;
        $fontFamily = $config->fontFamily->toCss();

        return <<<CSS
        :root {
            /* Color Scheme */
            --theme-color-primary: {$colors->primary->toHex()};
            --theme-color-primary-rgb: {$colors->primary->toRgbString()};
            --theme-color-secondary: {$colors->secondary->toHex()};
            --theme-color-secondary-rgb: {$colors->secondary->toRgbString()};
            --theme-color-background: {$colors->background->toHex()};
            --theme-color-background-rgb: {$colors->background->toRgbString()};
            --theme-color-text: {$colors->text->toHex()};
            --theme-color-text-rgb: {$colors->text->toRgbString()};

            /* Typography */
            --theme-font-family: {$fontFamily};
        }
        CSS;
    }

    private function generateUtilityClasses(ThemeConfig $config): string
    {
        return <<<CSS
        /* Utility Classes */
        .theme-bg-primary {
            background-color: var(--theme-color-primary);
        }

        .theme-bg-secondary {
            background-color: var(--theme-color-secondary);
        }

        .theme-text-primary {
            color: var(--theme-color-primary);
        }

        .theme-text-secondary {
            color: var(--theme-color-secondary);
        }

        .theme-font {
            font-family: var(--theme-font-family);
        }
        CSS;
    }

    private function generateLayoutClasses(ThemeConfig $config): string
    {
        $layout = $config->layoutSettings;
        $columnClass = $layout->columnCount->cssClass();
        $mode = $layout->mode->value;

        $css = <<<CSS
        /* Layout Classes */
        .theme-layout-{$mode} .theme-content-item {
            margin-bottom: 1.5rem;
        }
        CSS;

        if ($layout->isGridMode()) {
            $css .= <<<CSS


            .theme-layout-grid .theme-content-wrapper {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            @media (min-width: 768px) {
                .theme-layout-grid .theme-content-item {
                    grid-column: span 1;
                }
            }
            CSS;
        }

        if ($layout->isListMode()) {
            $css .= <<<CSS


            .theme-layout-list .theme-content-wrapper {
                display: flex;
                flex-direction: column;
            }

            .theme-layout-list .theme-content-item {
                width: 100%;
            }
            CSS;
        }

        if ($layout->showSidebar) {
            $css .= <<<CSS


            .theme-with-sidebar {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            @media (min-width: 992px) {
                .theme-with-sidebar {
                    grid-template-columns: 1fr 300px;
                }
            }
            CSS;
        }

        return $css;
    }
}
