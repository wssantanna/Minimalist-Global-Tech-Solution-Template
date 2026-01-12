<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Presentation\Customizer\Section;

use PHPUnit\Framework\TestCase;
use ThemeCore\Presentation\Customizer\Section\LayoutSection;

final class LayoutSectionTest extends TestCase
{
    private LayoutSection $section;

    protected function setUp(): void
    {
        $this->section = new LayoutSection();
    }

    public function test_sanitizes_layout_mode_with_valid_value(): void
    {
        // Act
        $result = $this->section->sanitizeLayoutMode('grid');

        // Assert
        self::assertEquals('grid', $result);
    }

    public function test_sanitizes_layout_mode_with_invalid_value(): void
    {
        // Act
        $result = $this->section->sanitizeLayoutMode('invalid');

        // Assert
        self::assertEquals('grid', $result);
    }

    public function test_sanitizes_layout_mode_accepts_all_valid_modes(): void
    {
        // Assert
        self::assertEquals('grid', $this->section->sanitizeLayoutMode('grid'));
        self::assertEquals('list', $this->section->sanitizeLayoutMode('list'));
        self::assertEquals('masonry', $this->section->sanitizeLayoutMode('masonry'));
    }

    public function test_sanitizes_column_count_with_valid_value(): void
    {
        // Act
        $result = $this->section->sanitizeColumnCount(3);

        // Assert
        self::assertEquals(3, $result);
    }

    public function test_sanitizes_column_count_with_too_low_value(): void
    {
        // Act
        $result = $this->section->sanitizeColumnCount(1);

        // Assert
        self::assertEquals(3, $result);
    }

    public function test_sanitizes_column_count_with_too_high_value(): void
    {
        // Act
        $result = $this->section->sanitizeColumnCount(5);

        // Assert
        self::assertEquals(3, $result);
    }

    public function test_sanitizes_column_count_accepts_all_valid_counts(): void
    {
        // Assert
        self::assertEquals(2, $this->section->sanitizeColumnCount(2));
        self::assertEquals(3, $this->section->sanitizeColumnCount(3));
        self::assertEquals(4, $this->section->sanitizeColumnCount(4));
    }

    public function test_sanitizes_checkbox_with_true(): void
    {
        // Act
        $result = $this->section->sanitizeCheckbox(true);

        // Assert
        self::assertTrue($result);
    }

    public function test_sanitizes_checkbox_with_false(): void
    {
        // Act
        $result = $this->section->sanitizeCheckbox(false);

        // Assert
        self::assertFalse($result);
    }

    public function test_sanitizes_checkbox_with_truthy_value(): void
    {
        // Act
        $result = $this->section->sanitizeCheckbox(1);

        // Assert
        self::assertTrue($result);
    }

    public function test_sanitizes_checkbox_with_falsy_value(): void
    {
        // Act
        $result = $this->section->sanitizeCheckbox(0);

        // Assert
        self::assertFalse($result);
    }
}
