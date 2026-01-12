<?php

declare(strict_types=1);

namespace ThemeCore\Application\DTO;

use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Enum\ColumnCount;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\ValueObject\HexColor;

/**
 * Data Transfer Object for updating Theme Settings
 *
 * Receives raw data and converts it to Domain Entities.
 */
final readonly class UpdateThemeSettingsDTO
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
     * Create DTO from array (useful for form data, API requests)
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            primaryColor: (string) ($data['primaryColor'] ?? '#0D6EFD'),
            secondaryColor: (string) ($data['secondaryColor'] ?? '#6C757D'),
            backgroundColor: (string) ($data['backgroundColor'] ?? '#FFFFFF'),
            textColor: (string) ($data['textColor'] ?? '#212529'),
            layoutMode: (string) ($data['layoutMode'] ?? 'grid'),
            columnCount: (int) ($data['columnCount'] ?? 3),
            showSidebar: (bool) ($data['showSidebar'] ?? true),
            fontFamily: (string) ($data['fontFamily'] ?? 'Open Sans'),
        );
    }

    /**
     * Convert DTO to Domain Entity
     *
     * @throws \InvalidArgumentException If validation fails
     */
    public function toEntity(): ThemeConfig
    {
        return new ThemeConfig(
            colorScheme: new ColorScheme(
                primary: new HexColor($this->primaryColor),
                secondary: new HexColor($this->secondaryColor),
                background: new HexColor($this->backgroundColor),
                text: new HexColor($this->textColor),
            ),
            layoutSettings: new LayoutSettings(
                mode: LayoutMode::from($this->layoutMode),
                columnCount: ColumnCount::from($this->columnCount),
                showSidebar: $this->showSidebar,
            ),
            fontFamily: new FontFamily($this->fontFamily),
        );
    }
}
