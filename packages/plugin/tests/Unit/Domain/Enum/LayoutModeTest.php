<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\Enum;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Enum\LayoutMode;

final class LayoutModeTest extends TestCase
{
    public function test_has_grid_case(): void
    {
        self::assertEquals('grid', LayoutMode::GRID->value);
    }

    public function test_has_list_case(): void
    {
        self::assertEquals('list', LayoutMode::LIST->value);
    }

    public function test_grid_label(): void
    {
        self::assertEquals('Grid View', LayoutMode::GRID->label());
    }

    public function test_list_label(): void
    {
        self::assertEquals('List View', LayoutMode::LIST->label());
    }

    public function test_can_create_from_string(): void
    {
        $mode = LayoutMode::from('grid');

        self::assertSame(LayoutMode::GRID, $mode);
    }

    public function test_all_cases_available(): void
    {
        $cases = LayoutMode::cases();

        self::assertCount(2, $cases);
        self::assertContains(LayoutMode::GRID, $cases);
        self::assertContains(LayoutMode::LIST, $cases);
    }
}
