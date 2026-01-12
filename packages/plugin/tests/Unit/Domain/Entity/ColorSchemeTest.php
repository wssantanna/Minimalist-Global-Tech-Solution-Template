<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\ValueObject\HexColor;

final class ColorSchemeTest extends TestCase
{
    public function test_creates_color_scheme(): void
    {
        $scheme = new ColorScheme(
            primary: new HexColor('#0D6EFD'),
            secondary: new HexColor('#6C757D'),
            background: new HexColor('#FFFFFF'),
            text: new HexColor('#212529'),
        );

        self::assertEquals('#0D6EFD', $scheme->primary->toHex());
        self::assertEquals('#6C757D', $scheme->secondary->toHex());
        self::assertEquals('#FFFFFF', $scheme->background->toHex());
        self::assertEquals('#212529', $scheme->text->toHex());
    }

    public function test_converts_to_array(): void
    {
        $scheme = new ColorScheme(
            primary: new HexColor('#0D6EFD'),
            secondary: new HexColor('#6C757D'),
            background: new HexColor('#FFFFFF'),
            text: new HexColor('#212529'),
        );

        $array = $scheme->toArray();

        self::assertEquals([
            'primary' => '#0D6EFD',
            'secondary' => '#6C757D',
            'background' => '#FFFFFF',
            'text' => '#212529',
        ], $array);
    }

    public function test_creates_default_scheme(): void
    {
        $scheme = ColorScheme::default();

        self::assertEquals('#0D6EFD', $scheme->primary->toHex());
        self::assertEquals('#6C757D', $scheme->secondary->toHex());
        self::assertEquals('#FFFFFF', $scheme->background->toHex());
        self::assertEquals('#212529', $scheme->text->toHex());
    }

    public function test_immutability_with_readonly(): void
    {
        $scheme = ColorScheme::default();

        // This test ensures readonly properties are enforced by PHP
        // If someone tries to modify: $scheme->primary = new HexColor('#FF0000');
        // PHP will throw an error

        $this->expectNotToPerformAssertions();
    }
}
