<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Enum;

enum ColumnCount: int
{
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;

    public function label(): string
    {
        return match ($this) {
            self::TWO => '2 Columns',
            self::THREE => '3 Columns',
            self::FOUR => '4 Columns',
        };
    }

    public function cssClass(): string
    {
        return match ($this) {
            self::TWO => 'col-md-6',
            self::THREE => 'col-md-4',
            self::FOUR => 'col-md-3',
        };
    }
}
