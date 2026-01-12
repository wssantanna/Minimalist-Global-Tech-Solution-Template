<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Enum;

enum LayoutMode: string
{
    case GRID = 'grid';
    case LIST = 'list';

    public function label(): string
    {
        return match ($this) {
            self::GRID => 'Grid View',
            self::LIST => 'List View',
        };
    }
}
