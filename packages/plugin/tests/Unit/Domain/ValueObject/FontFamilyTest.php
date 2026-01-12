<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\Exception\InvalidFontFamilyException;

final class FontFamilyTest extends TestCase
{
    public function test_creates_valid_font_family(): void
    {
        $font = new FontFamily('Arial');

        self::assertEquals('Arial', $font->toCss());
    }

    public function test_creates_font_family_with_spaces(): void
    {
        $font = new FontFamily('Open Sans');

        self::assertEquals("'Open Sans'", $font->toCss());
    }

    public function test_preserves_quoted_font_family(): void
    {
        $font = new FontFamily('"Roboto Slab"');

        self::assertEquals('"Roboto Slab"', $font->toCss());
    }

    public function test_handles_font_stack(): void
    {
        $font = new FontFamily('Arial, Helvetica, sans-serif');

        self::assertEquals('Arial, Helvetica, sans-serif', $font->toCss());
    }

    public function test_trims_whitespace(): void
    {
        $font1 = new FontFamily('  Arial  ');
        $font2 = new FontFamily('Arial');

        self::assertTrue($font1->equals($font2));
    }

    public function test_compares_fonts_case_insensitive(): void
    {
        $font1 = new FontFamily('Arial');
        $font2 = new FontFamily('arial');

        self::assertTrue($font1->equals($font2));
    }

    public function test_different_fonts_are_not_equal(): void
    {
        $font1 = new FontFamily('Arial');
        $font2 = new FontFamily('Helvetica');

        self::assertFalse($font1->equals($font2));
    }

    public function test_throws_exception_for_empty_string(): void
    {
        $this->expectException(InvalidFontFamilyException::class);
        $this->expectExceptionMessage('Font family cannot be empty');

        new FontFamily('');
    }

    public function test_throws_exception_for_whitespace_only(): void
    {
        $this->expectException(InvalidFontFamilyException::class);

        new FontFamily('   ');
    }

    public function test_throws_exception_for_invalid_characters(): void
    {
        $this->expectException(InvalidFontFamilyException::class);
        $this->expectExceptionMessage('contains invalid characters');

        new FontFamily('Arial<script>');
    }
}
