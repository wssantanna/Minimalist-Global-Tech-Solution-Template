<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Entity;

use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\Enum\ColumnCount;

final readonly class LayoutSettings
{
    public function __construct(
        public LayoutMode $mode,
        public ColumnCount $columnCount,
        public bool $showSidebar,
    ) {
    }

    /**
     * @return array{mode: string, columnCount: int, showSidebar: bool}
     */
    public function toArray(): array
    {
        return [
            'mode' => $this->mode->value,
            'columnCount' => $this->columnCount->value,
            'showSidebar' => $this->showSidebar,
        ];
    }

    public static function default(): self
    {
        return new self(
            mode: LayoutMode::GRID,
            columnCount: ColumnCount::THREE,
            showSidebar: true,
        );
    }

    public function isGridMode(): bool
    {
        return $this->mode === LayoutMode::GRID;
    }

    public function isListMode(): bool
    {
        return $this->mode === LayoutMode::LIST;
    }
}
