<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Exception;

final class InvalidFontFamilyException extends \InvalidArgumentException
{
    public static function fromEmptyValue(): self
    {
        return new self('Font family cannot be empty');
    }

    public static function fromInvalidCharacters(string $value): self
    {
        return new self(
            "Invalid font family '{$value}': contains invalid characters"
        );
    }
}
