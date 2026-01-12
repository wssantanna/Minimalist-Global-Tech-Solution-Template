<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Integration\Adapter;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Enum\ColumnCount;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\ValueObject\HexColor;
use ThemeCore\Infrastructure\Adapter\WPCSSGenerator;

/**
 * @group integration
 */
final class WPCSSGeneratorTest extends TestCase
{
    private WPCSSGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new WPCSSGenerator();
    }

    public function test_generates_css_variables(): void
    {
        $config = ThemeConfig::default();
        $css = $this->generator->generateCssVariables($config);

        $this->assertStringContainsString(':root {', $css);
        $this->assertStringContainsString('--theme-color-primary: #0D6EFD;', $css);
        $this->assertStringContainsString('--theme-color-secondary: #6C757D;', $css);
        $this->assertStringContainsString('--theme-font-family: \'Open Sans\';', $css);
    }

    public function test_generates_rgb_variables(): void
    {
        $config = ThemeConfig::default();
        $css = $this->generator->generateCssVariables($config);

        $this->assertStringContainsString('--theme-color-primary-rgb:', $css);
        $this->assertStringContainsString('--theme-color-secondary-rgb:', $css);
    }

    public function test_generates_complete_css(): void
    {
        $config = ThemeConfig::default();
        $css = $this->generator->generate($config);

        $this->assertStringContainsString(':root {', $css);
        $this->assertStringContainsString('.theme-bg-primary', $css);
        $this->assertStringContainsString('.theme-text-primary', $css);
        $this->assertStringContainsString('.theme-layout-', $css);
    }

    public function test_generates_grid_layout_css(): void
    {
        $config = new ThemeConfig(
            colorScheme: ColorScheme::default(),
            layoutSettings: new LayoutSettings(
                mode: LayoutMode::GRID,
                columnCount: ColumnCount::THREE,
                showSidebar: true
            ),
            fontFamily: new FontFamily('Arial')
        );

        $css = $this->generator->generate($config);

        $this->assertStringContainsString('.theme-layout-grid', $css);
        $this->assertStringContainsString('grid-template-columns', $css);
    }

    public function test_generates_list_layout_css(): void
    {
        $config = new ThemeConfig(
            colorScheme: ColorScheme::default(),
            layoutSettings: new LayoutSettings(
                mode: LayoutMode::LIST,
                columnCount: ColumnCount::TWO,
                showSidebar: false
            ),
            fontFamily: new FontFamily('Arial')
        );

        $css = $this->generator->generate($config);

        $this->assertStringContainsString('.theme-layout-list', $css);
        $this->assertStringContainsString('flex-direction: column', $css);
    }

    public function test_generates_sidebar_css_when_enabled(): void
    {
        $config = new ThemeConfig(
            colorScheme: ColorScheme::default(),
            layoutSettings: new LayoutSettings(
                mode: LayoutMode::GRID,
                columnCount: ColumnCount::THREE,
                showSidebar: true
            ),
            fontFamily: new FontFamily('Arial')
        );

        $css = $this->generator->generate($config);

        $this->assertStringContainsString('.theme-with-sidebar', $css);
    }

    public function test_minified_css_removes_whitespace(): void
    {
        $config = ThemeConfig::default();
        $normalCss = $this->generator->generate($config);
        $minifiedCss = $this->generator->generateMinified($config);

        $this->assertLessThan(strlen($normalCss), strlen($minifiedCss));
        $this->assertStringNotContainsString("\n", $minifiedCss);
    }

    public function test_minified_css_preserves_functionality(): void
    {
        $config = ThemeConfig::default();
        $minifiedCss = $this->generator->generateMinified($config);

        $this->assertStringContainsString(':root{', $minifiedCss);
        $this->assertStringContainsString('--theme-color-primary:', $minifiedCss);
        $this->assertStringContainsString('.theme-bg-primary{', $minifiedCss);
    }
}
