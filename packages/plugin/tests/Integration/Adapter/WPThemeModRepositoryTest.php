<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Integration\Adapter;

use PHPUnit\Framework\TestCase;
use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Enum\ColumnCount;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\ValueObject\HexColor;
use ThemeCore\Infrastructure\Adapter\WPThemeModRepository;

/**
 * @group integration
 * @group wordpress
 */
final class WPThemeModRepositoryTest extends TestCase
{
    private WPThemeModRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new WPThemeModRepository();

        // Clean up before each test
        $this->repository->delete();
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        $this->repository->delete();
        parent::tearDown();
    }

    public function test_returns_default_config_when_no_data_exists(): void
    {
        $config = $this->repository->get();

        $this->assertInstanceOf(ThemeConfig::class, $config);
        $this->assertEquals('#0D6EFD', $config->colorScheme->primary->toHex());
        $this->assertEquals('Open Sans', $config->fontFamily->value);
    }

    public function test_saves_and_retrieves_config(): void
    {
        $config = new ThemeConfig(
            colorScheme: new ColorScheme(
                primary: new HexColor('#FF5733'),
                secondary: new HexColor('#33FF57'),
                background: new HexColor('#F0F0F0'),
                text: new HexColor('#333333')
            ),
            layoutSettings: new LayoutSettings(
                mode: LayoutMode::LIST,
                columnCount: ColumnCount::FOUR,
                showSidebar: false
            ),
            fontFamily: new FontFamily('Roboto')
        );

        $this->repository->save($config);
        $retrieved = $this->repository->get();

        $this->assertEquals('#FF5733', $retrieved->colorScheme->primary->toHex());
        $this->assertEquals('#33FF57', $retrieved->colorScheme->secondary->toHex());
        $this->assertEquals('list', $retrieved->layoutSettings->mode->value);
        $this->assertEquals(4, $retrieved->layoutSettings->columnCount->value);
        $this->assertFalse($retrieved->layoutSettings->showSidebar);
        $this->assertEquals('Roboto', $retrieved->fontFamily->value);
    }

    public function test_exists_returns_false_when_no_data(): void
    {
        $this->assertFalse($this->repository->exists());
    }

    public function test_exists_returns_true_after_save(): void
    {
        $config = ThemeConfig::default();
        $this->repository->save($config);

        $this->assertTrue($this->repository->exists());
    }

    public function test_delete_removes_config(): void
    {
        $config = ThemeConfig::default();
        $this->repository->save($config);
        $this->assertTrue($this->repository->exists());

        $this->repository->delete();

        $this->assertFalse($this->repository->exists());
    }

    public function test_handles_partial_data_gracefully(): void
    {
        // Simulate partial/corrupted data
        set_theme_mod('theme_core_config', ['incomplete' => 'data']);

        $config = $this->repository->get();

        // Should return default config when data is invalid
        $this->assertInstanceOf(ThemeConfig::class, $config);
        $this->assertEquals('#0D6EFD', $config->colorScheme->primary->toHex());
    }
}
