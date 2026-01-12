<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\ValueObject\HexColor;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\Enum\ColumnCount;

final class ThemeConfigTest extends TestCase
{
    public function test_creates_theme_config(): void
    {
        $config = new ThemeConfig(
            colorScheme: ColorScheme::default(),
            layoutSettings: LayoutSettings::default(),
            fontFamily: new FontFamily('Arial'),
        );

        self::assertInstanceOf(ColorScheme::class, $config->colorScheme);
        self::assertInstanceOf(LayoutSettings::class, $config->layoutSettings);
        self::assertInstanceOf(FontFamily::class, $config->fontFamily);
    }

    public function test_converts_to_array(): void
    {
        $colorScheme = new ColorScheme(
            primary: new HexColor('#FF0000'),
            secondary: new HexColor('#00FF00'),
            background: new HexColor('#0000FF'),
            text: new HexColor('#FFFFFF'),
        );

        $layoutSettings = new LayoutSettings(
            mode: LayoutMode::LIST,
            columnCount: ColumnCount::TWO,
            showSidebar: false,
        );

        $fontFamily = new FontFamily('Roboto');

        $config = new ThemeConfig(
            colorScheme: $colorScheme,
            layoutSettings: $layoutSettings,
            fontFamily: $fontFamily,
        );

        $array = $config->toArray();

        self::assertEquals([
            'colorScheme' => [
                'primary' => '#FF0000',
                'secondary' => '#00FF00',
                'background' => '#0000FF',
                'text' => '#FFFFFF',
            ],
            'layoutSettings' => [
                'mode' => 'list',
                'columnCount' => 2,
                'showSidebar' => false,
            ],
            'fontFamily' => 'Roboto',
        ], $array);
    }

    public function test_creates_default_config(): void
    {
        $config = ThemeConfig::default();

        self::assertEquals('#0D6EFD', $config->colorScheme->primary->toHex());
        self::assertSame(LayoutMode::GRID, $config->layoutSettings->mode);
        self::assertEquals("'Open Sans'", $config->fontFamily->toCss());
    }
}
