<?php

declare(strict_types=1);

namespace ThemeCore\Infrastructure\Adapter;

use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Enum\ColumnCount;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\Port\IThemeRepository;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\ValueObject\HexColor;

final class WPThemeModRepository implements IThemeRepository
{
    private const THEME_MOD_KEY = 'theme_core_config';

    public function get(): ThemeConfig
    {
        $data = get_theme_mod(self::THEME_MOD_KEY);

        if (!is_array($data) || !$this->isValidData($data)) {
            return ThemeConfig::default();
        }

        return $this->hydrate($data);
    }

    public function save(ThemeConfig $config): void
    {
        set_theme_mod(self::THEME_MOD_KEY, $config->toArray());
    }

    public function exists(): bool
    {
        $data = get_theme_mod(self::THEME_MOD_KEY);
        return is_array($data) && !empty($data);
    }

    public function delete(): void
    {
        remove_theme_mod(self::THEME_MOD_KEY);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function isValidData(array $data): bool
    {
        return isset($data['colorScheme'], $data['layoutSettings'], $data['fontFamily'])
            && is_array($data['colorScheme'])
            && is_array($data['layoutSettings'])
            && is_string($data['fontFamily']);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function hydrate(array $data): ThemeConfig
    {
        $colorScheme = $this->hydrateColorScheme($data['colorScheme']);
        $layoutSettings = $this->hydrateLayoutSettings($data['layoutSettings']);
        $fontFamily = new FontFamily($data['fontFamily']);

        return new ThemeConfig(
            colorScheme: $colorScheme,
            layoutSettings: $layoutSettings,
            fontFamily: $fontFamily
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function hydrateColorScheme(array $data): ColorScheme
    {
        return new ColorScheme(
            primary: new HexColor($data['primary'] ?? '#0D6EFD'),
            secondary: new HexColor($data['secondary'] ?? '#6C757D'),
            background: new HexColor($data['background'] ?? '#FFFFFF'),
            text: new HexColor($data['text'] ?? '#212529')
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function hydrateLayoutSettings(array $data): LayoutSettings
    {
        $mode = LayoutMode::tryFrom($data['mode'] ?? 'grid') ?? LayoutMode::GRID;
        $columnCount = ColumnCount::tryFrom($data['columnCount'] ?? 3) ?? ColumnCount::THREE;
        $showSidebar = (bool) ($data['showSidebar'] ?? true);

        return new LayoutSettings(
            mode: $mode,
            columnCount: $columnCount,
            showSidebar: $showSidebar
        );
    }
}
