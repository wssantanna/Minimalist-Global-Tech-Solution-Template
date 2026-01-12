<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Entity;

use ThemeCore\Domain\ValueObject\HexColor;

final readonly class ColorScheme
{
    public function __construct(
        public HexColor $primary,
        public HexColor $secondary,
        public HexColor $background,
        public HexColor $text,
    ) {
    }

    /**
     * @return array{primary: string, secondary: string, background: string, text: string}
     */
    public function toArray(): array
    {
        return [
            'primary' => $this->primary->toHex(),
            'secondary' => $this->secondary->toHex(),
            'background' => $this->background->toHex(),
            'text' => $this->text->toHex(),
        ];
    }

    public static function default(): self
    {
        return new self(
            primary: new HexColor('#0D6EFD'),
            secondary: new HexColor('#6C757D'),
            background: new HexColor('#FFFFFF'),
            text: new HexColor('#212529'),
        );
    }
}
