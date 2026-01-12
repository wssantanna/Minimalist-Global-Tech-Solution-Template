<?php

declare(strict_types=1);

namespace ThemeCore\Application\DTO;

use ThemeCore\Domain\Entity\ThemeConfig;

/**
 * Data Transfer Object for Theme Settings
 *
 * Used to transfer data between layers without exposing domain entities directly.
 */
final readonly class ThemeSettingsDTO
{
    public function __construct(
        public string $primaryColor,
        public string $secondaryColor,
        public string $backgroundColor,
        public string $textColor,
        public string $layoutMode,
        public int $columnCount,
        public bool $showSidebar,
        public string $fontFamily,
    ) {
    }

    /**
     * Create DTO from Domain Entity
     */
    public static function fromEntity(ThemeConfig $config): self
    {
        return new self(
            primaryColor: $config->colorScheme->primary->toHex(),
            secondaryColor: $config->colorScheme->secondary->toHex(),
            backgroundColor: $config->colorScheme->background->toHex(),
            textColor: $config->colorScheme->text->toHex(),
            layoutMode: $config->layoutSettings->mode->value,
            columnCount: $config->layoutSettings->columnCount->value,
            showSidebar: $config->layoutSettings->showSidebar,
            fontFamily: $config->fontFamily->value,
        );
    }

    /**
     * Convert DTO to array (useful for API responses or serialization)
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'primaryColor' => $this->primaryColor,
            'secondaryColor' => $this->secondaryColor,
            'backgroundColor' => $this->backgroundColor,
            'textColor' => $this->textColor,
            'layoutMode' => $this->layoutMode,
            'columnCount' => $this->columnCount,
            'showSidebar' => $this->showSidebar,
            'fontFamily' => $this->fontFamily,
        ];
    }
}
