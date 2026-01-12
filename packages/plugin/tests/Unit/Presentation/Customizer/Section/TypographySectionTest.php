<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Presentation\Customizer\Section;

use PHPUnit\Framework\TestCase;
use ThemeCore\Presentation\Customizer\Section\TypographySection;

final class TypographySectionTest extends TestCase
{
    private TypographySection $section;

    protected function setUp(): void
    {
        $this->section = new TypographySection();
    }

    public function test_sanitizes_font_family_with_valid_value(): void
    {
        // Act
        $result = $this->section->sanitizeFontFamily('Arial');

        // Assert
        self::assertEquals('Arial', $result);
    }

    public function test_sanitizes_font_family_with_invalid_value(): void
    {
        // Act
        $result = $this->section->sanitizeFontFamily('InvalidFont');

        // Assert
        self::assertEquals('Open Sans', $result);
    }

    public function test_sanitizes_font_family_accepts_all_valid_fonts(): void
    {
        $validFonts = [
            'Open Sans',
            'Roboto',
            'Lato',
            'Montserrat',
            'Poppins',
            'Inter',
            'Arial',
            'Helvetica',
            'Georgia',
            'Times New Roman',
        ];

        foreach ($validFonts as $font) {
            self::assertEquals($font, $this->section->sanitizeFontFamily($font));
        }
    }

    public function test_sanitizes_font_family_returns_default_for_empty_string(): void
    {
        // Act
        $result = $this->section->sanitizeFontFamily('');

        // Assert
        self::assertEquals('Open Sans', $result);
    }
}
