<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Entity;

use ThemeCore\Domain\ValueObject\FontFamily;

final readonly class ThemeConfig
{
    public function __construct(
        public ColorScheme $colorScheme,
        public LayoutSettings $layoutSettings,
        public FontFamily $fontFamily,
    ) {
    }

    /**
     * @return array{colorScheme: array{primary: string, secondary: string, background: string, text: string}, layoutSettings: array{mode: string, columnCount: int, showSidebar: bool}, fontFamily: string}
     */
    public function toArray(): array
    {
        return [
            'colorScheme' => $this->colorScheme->toArray(),
            'layoutSettings' => $this->layoutSettings->toArray(),
            'fontFamily' => $this->fontFamily->toCss(),
        ];
    }

    public static function default(): self
    {
        return new self(
            colorScheme: ColorScheme::default(),
            layoutSettings: LayoutSettings::default(),
            fontFamily: new FontFamily('Open Sans'),
        );
    }
}
