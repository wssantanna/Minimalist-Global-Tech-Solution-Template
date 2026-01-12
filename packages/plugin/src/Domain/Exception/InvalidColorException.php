<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Exception;

final class InvalidColorException extends \InvalidArgumentException
{
    public static function fromInvalidFormat(string $value): self
    {
        return new self(
            "Invalid hex color format: '{$value}'. Expected format: #RRGGBB"
        );
    }
}
