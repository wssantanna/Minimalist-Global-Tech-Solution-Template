<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\Entity;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\Enum\ColumnCount;

final class LayoutSettingsTest extends TestCase
{
    public function test_creates_layout_settings(): void
    {
        $settings = new LayoutSettings(
            mode: LayoutMode::GRID,
            columnCount: ColumnCount::THREE,
            showSidebar: true,
        );

        self::assertSame(LayoutMode::GRID, $settings->mode);
        self::assertSame(ColumnCount::THREE, $settings->columnCount);
        self::assertTrue($settings->showSidebar);
    }

    public function test_converts_to_array(): void
    {
        $settings = new LayoutSettings(
            mode: LayoutMode::LIST,
            columnCount: ColumnCount::TWO,
            showSidebar: false,
        );

        $array = $settings->toArray();

        self::assertEquals([
            'mode' => 'list',
            'columnCount' => 2,
            'showSidebar' => false,
        ], $array);
    }

    public function test_creates_default_settings(): void
    {
        $settings = LayoutSettings::default();

        self::assertSame(LayoutMode::GRID, $settings->mode);
        self::assertSame(ColumnCount::THREE, $settings->columnCount);
        self::assertTrue($settings->showSidebar);
    }

    public function test_checks_if_grid_mode(): void
    {
        $gridSettings = new LayoutSettings(
            mode: LayoutMode::GRID,
            columnCount: ColumnCount::THREE,
            showSidebar: true,
        );

        self::assertTrue($gridSettings->isGridMode());
        self::assertFalse($gridSettings->isListMode());
    }

    public function test_checks_if_list_mode(): void
    {
        $listSettings = new LayoutSettings(
            mode: LayoutMode::LIST,
            columnCount: ColumnCount::TWO,
            showSidebar: false,
        );

        self::assertTrue($listSettings->isListMode());
        self::assertFalse($listSettings->isGridMode());
    }
}
