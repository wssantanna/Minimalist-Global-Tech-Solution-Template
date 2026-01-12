<?php

declare(strict_types=1);

namespace ThemeCore\Domain\ValueObject;

use ThemeCore\Domain\Exception\InvalidFontFamilyException;

final readonly class FontFamily
{
    public function __construct(
        public string $value
    ) {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw InvalidFontFamilyException::fromEmptyValue();
        }

        // Basic validation: only allow alphanumeric, spaces, hyphens, and common font chars
        if (!preg_match('/^[\w\s\-,\'\"]+$/u', $trimmed)) {
            throw InvalidFontFamilyException::fromInvalidCharacters($value);
        }
    }

    public function toCss(): string
    {
        $value = trim($this->value);

        // Don't quote if it's a font stack (contains commas) or already quoted
        if (str_contains($value, ',') || str_starts_with($value, '"') || str_starts_with($value, "'")) {
            return $value;
        }

        // Quote fonts with spaces (e.g., "Open Sans")
        if (str_contains($value, ' ')) {
            return "'{$value}'";
        }

        return $value;
    }

    public function equals(FontFamily $other): bool
    {
        return strcasecmp(trim($this->value), trim($other->value)) === 0;
    }
}
