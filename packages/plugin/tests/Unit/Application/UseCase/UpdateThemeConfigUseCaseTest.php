<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Application\UseCase;

use PHPUnit\Framework\TestCase;
use ThemeCore\Application\DTO\UpdateThemeSettingsDTO;
use ThemeCore\Application\UseCase\UpdateThemeConfigUseCase;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Exception\InvalidColorException;
use ThemeCore\Domain\Port\ICacheService;
use ThemeCore\Domain\Port\IThemeRepository;

final class UpdateThemeConfigUseCaseTest extends TestCase
{
    public function test_executes_successfully(): void
    {
        // Arrange
        $settings = new UpdateThemeSettingsDTO(
            primaryColor: '#0D6EFD',
            secondaryColor: '#6C757D',
            backgroundColor: '#FFFFFF',
            textColor: '#212529',
            layoutMode: 'grid',
            columnCount: 3,
            showSidebar: true,
            fontFamily: 'Arial',
        );

        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(ThemeConfig::class));

        $cache = $this->createMock(ICacheService::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('dynamic_css')
;

        $useCase = new UpdateThemeConfigUseCase($repository, $cache);

        // Act
        $useCase->execute($settings);

        // Assert - expectations verified by mocks
        $this->assertTrue(true);
    }

    public function test_invalidates_cache_after_update(): void
    {
        // Arrange
        $settings = UpdateThemeSettingsDTO::fromArray([
            'primaryColor' => '#FF0000',
            'secondaryColor' => '#000000',
            'backgroundColor' => '#FFFFFF',
            'textColor' => '#000000',
        ]);

        $repository = $this->createMock(IThemeRepository::class);

        $cache = $this->createMock(ICacheService::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('dynamic_css');

        $useCase = new UpdateThemeConfigUseCase($repository, $cache);

        // Act
        $useCase->execute($settings);

        // Assert - expectations verified by mock
        $this->assertTrue(true);
    }

    public function test_throws_exception_for_invalid_color(): void
    {
        // Arrange
        $settings = new UpdateThemeSettingsDTO(
            primaryColor: 'invalid-color', // Invalid hex color
            secondaryColor: '#6C757D',
            backgroundColor: '#FFFFFF',
            textColor: '#212529',
            layoutMode: 'grid',
            columnCount: 3,
            showSidebar: true,
            fontFamily: 'Arial',
        );

        $repository = $this->createMock(IThemeRepository::class);
        $cache = $this->createMock(ICacheService::class);

        $useCase = new UpdateThemeConfigUseCase($repository, $cache);

        // Assert
        $this->expectException(InvalidColorException::class);

        // Act
        $useCase->execute($settings);
    }

    public function test_creates_dto_from_array(): void
    {
        // Arrange
        $data = [
            'primaryColor' => '#0D6EFD',
            'secondaryColor' => '#6C757D',
            'backgroundColor' => '#FFFFFF',
            'textColor' => '#212529',
            'layoutMode' => 'list',
            'columnCount' => 4,
            'showSidebar' => false,
            'fontFamily' => 'Georgia',
        ];

        // Act
        $dto = UpdateThemeSettingsDTO::fromArray($data);

        // Assert
        self::assertEquals('#0D6EFD', $dto->primaryColor);
        self::assertEquals('list', $dto->layoutMode);
        self::assertEquals(4, $dto->columnCount);
        self::assertFalse($dto->showSidebar);
        self::assertEquals('Georgia', $dto->fontFamily);
    }

    public function test_uses_defaults_when_data_missing(): void
    {
        // Arrange
        $data = []; // Empty array

        // Act
        $dto = UpdateThemeSettingsDTO::fromArray($data);

        // Assert
        self::assertEquals('#0D6EFD', $dto->primaryColor);
        self::assertEquals('#6C757D', $dto->secondaryColor);
        self::assertEquals('#FFFFFF', $dto->backgroundColor);
        self::assertEquals('#212529', $dto->textColor);
        self::assertEquals('grid', $dto->layoutMode);
        self::assertEquals(3, $dto->columnCount);
        self::assertTrue($dto->showSidebar);
        self::assertEquals('Open Sans', $dto->fontFamily);
    }
}
