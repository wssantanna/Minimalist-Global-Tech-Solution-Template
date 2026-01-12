<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\ValueObject\HexColor;
use ThemeCore\Domain\Exception\InvalidColorException;

final class HexColorTest extends TestCase
{
    public function test_creates_valid_hex_color(): void
    {
        $color = new HexColor('#0D6EFD');

        self::assertEquals('#0D6EFD', $color->toHex());
    }

    public function test_normalizes_to_uppercase(): void
    {
        $color = new HexColor('#0d6efd');

        self::assertEquals('#0D6EFD', $color->toHex());
    }

    public function test_converts_to_rgb_array(): void
    {
        $color = new HexColor('#0D6EFD');

        $rgb = $color->toRgb();

        self::assertEquals(['r' => 13, 'g' => 110, 'b' => 253], $rgb);
    }

    public function test_converts_to_rgb_string(): void
    {
        $color = new HexColor('#0D6EFD');

        self::assertEquals('13, 110, 253', $color->toRgbString());
    }

    public function test_compares_colors_case_insensitive(): void
    {
        $color1 = new HexColor('#0D6EFD');
        $color2 = new HexColor('#0d6efd');

        self::assertTrue($color1->equals($color2));
    }

    public function test_different_colors_are_not_equal(): void
    {
        $color1 = new HexColor('#0D6EFD');
        $color2 = new HexColor('#FF0000');

        self::assertFalse($color1->equals($color2));
    }

    public function test_throws_exception_for_missing_hash(): void
    {
        $this->expectException(InvalidColorException::class);
        $this->expectExceptionMessage('Invalid hex color format');

        new HexColor('0D6EFD');
    }

    public function test_throws_exception_for_short_hex(): void
    {
        $this->expectException(InvalidColorException::class);

        new HexColor('#0D6');
    }

    public function test_throws_exception_for_long_hex(): void
    {
        $this->expectException(InvalidColorException::class);

        new HexColor('#0D6EFD00');
    }

    public function test_throws_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidColorException::class);

        new HexColor('#GGGGGG');
    }

    public function test_throws_exception_for_empty_string(): void
    {
        $this->expectException(InvalidColorException::class);

        new HexColor('');
    }
}
