<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Domain\Enum;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Enum\ColumnCount;

final class ColumnCountTest extends TestCase
{
    public function test_has_two_case(): void
    {
        self::assertEquals(2, ColumnCount::TWO->value);
    }

    public function test_has_three_case(): void
    {
        self::assertEquals(3, ColumnCount::THREE->value);
    }

    public function test_has_four_case(): void
    {
        self::assertEquals(4, ColumnCount::FOUR->value);
    }

    public function test_two_columns_label(): void
    {
        self::assertEquals('2 Columns', ColumnCount::TWO->label());
    }

    public function test_three_columns_label(): void
    {
        self::assertEquals('3 Columns', ColumnCount::THREE->label());
    }

    public function test_four_columns_label(): void
    {
        self::assertEquals('4 Columns', ColumnCount::FOUR->label());
    }

    public function test_two_columns_css_class(): void
    {
        self::assertEquals('col-md-6', ColumnCount::TWO->cssClass());
    }

    public function test_three_columns_css_class(): void
    {
        self::assertEquals('col-md-4', ColumnCount::THREE->cssClass());
    }

    public function test_four_columns_css_class(): void
    {
        self::assertEquals('col-md-3', ColumnCount::FOUR->cssClass());
    }

    public function test_can_create_from_int(): void
    {
        $count = ColumnCount::from(3);

        self::assertSame(ColumnCount::THREE, $count);
    }

    public function test_all_cases_available(): void
    {
        $cases = ColumnCount::cases();

        self::assertCount(3, $cases);
        self::assertContains(ColumnCount::TWO, $cases);
        self::assertContains(ColumnCount::THREE, $cases);
        self::assertContains(ColumnCount::FOUR, $cases);
    }
}
