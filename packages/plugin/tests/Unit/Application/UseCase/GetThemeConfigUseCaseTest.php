<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Application\UseCase;

use PHPUnit\Framework\TestCase;
use ThemeCore\Application\DTO\ThemeSettingsDTO;
use ThemeCore\Application\UseCase\GetThemeConfigUseCase;
use ThemeCore\Domain\Entity\ColorScheme;
use ThemeCore\Domain\Entity\LayoutSettings;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Enum\ColumnCount;
use ThemeCore\Domain\Enum\LayoutMode;
use ThemeCore\Domain\Port\IThemeRepository;
use ThemeCore\Domain\ValueObject\FontFamily;
use ThemeCore\Domain\ValueObject\HexColor;

final class GetThemeConfigUseCaseTest extends TestCase
{
    public function test_executes_successfully(): void
    {
        // Arrange
        $expectedConfig = new ThemeConfig(
            colorScheme: new ColorScheme(
                primary: new HexColor('#0D6EFD'),
                secondary: new HexColor('#6C757D'),
                background: new HexColor('#FFFFFF'),
                text: new HexColor('#212529'),
            ),
            layoutSettings: new LayoutSettings(
                mode: LayoutMode::GRID,
                columnCount: ColumnCount::THREE,
                showSidebar: true,
            ),
            fontFamily: new FontFamily('Arial'),
        );

        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->expects($this->once())
            ->method('get')
            ->willReturn($expectedConfig);

        $useCase = new GetThemeConfigUseCase($repository);

        // Act
        $result = $useCase->execute();

        // Assert
        self::assertInstanceOf(ThemeSettingsDTO::class, $result);
        self::assertEquals('#0D6EFD', $result->primaryColor);
        self::assertEquals('#6C757D', $result->secondaryColor);
        self::assertEquals('#FFFFFF', $result->backgroundColor);
        self::assertEquals('#212529', $result->textColor);
        self::assertEquals('grid', $result->layoutMode);
        self::assertEquals(3, $result->columnCount);
        self::assertTrue($result->showSidebar);
        self::assertEquals('Arial', $result->fontFamily);
    }

    public function test_returns_default_config(): void
    {
        // Arrange
        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->method('get')
            ->willReturn(ThemeConfig::default());

        $useCase = new GetThemeConfigUseCase($repository);

        // Act
        $result = $useCase->execute();

        // Assert
        self::assertEquals('Open Sans', $result->fontFamily);
        self::assertEquals('grid', $result->layoutMode);
    }

    public function test_converts_to_array_correctly(): void
    {
        // Arrange
        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->method('get')
            ->willReturn(ThemeConfig::default());

        $useCase = new GetThemeConfigUseCase($repository);

        // Act
        $result = $useCase->execute();
        $array = $result->toArray();

        // Assert
        self::assertIsArray($array);
        self::assertArrayHasKey('primaryColor', $array);
        self::assertArrayHasKey('secondaryColor', $array);
        self::assertArrayHasKey('backgroundColor', $array);
        self::assertArrayHasKey('textColor', $array);
        self::assertArrayHasKey('layoutMode', $array);
        self::assertArrayHasKey('columnCount', $array);
        self::assertArrayHasKey('showSidebar', $array);
        self::assertArrayHasKey('fontFamily', $array);
    }
}
