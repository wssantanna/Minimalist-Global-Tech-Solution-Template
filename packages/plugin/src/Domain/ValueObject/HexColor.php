<?php

declare(strict_types=1);

namespace ThemeCore\Domain\ValueObject;

use ThemeCore\Domain\Exception\InvalidColorException;

final readonly class HexColor
{
    public function __construct(
        public string $value
    ) {
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $value)) {
            throw InvalidColorException::fromInvalidFormat($value);
        }
    }

    public function toHex(): string
    {
        return strtoupper($this->value);
    }

    /**
     * @return array{r: int, g: int, b: int}
     */
    public function toRgb(): array
    {
        sscanf($this->value, "#%02x%02x%02x", $r, $g, $b);
        return ['r' => (int) $r, 'g' => (int) $g, 'b' => (int) $b];
    }

    public function toRgbString(): string
    {
        $rgb = $this->toRgb();
        return "{$rgb['r']}, {$rgb['g']}, {$rgb['b']}";
    }

    public function equals(HexColor $other): bool
    {
        return strcasecmp($this->value, $other->value) === 0;
    }
}
