<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Application\UseCase;

use PHPUnit\Framework\TestCase;
use ThemeCore\Application\UseCase\GenerateDynamicCSSUseCase;
use ThemeCore\Domain\Entity\ThemeConfig;
use ThemeCore\Domain\Port\ICacheService;
use ThemeCore\Domain\Port\ICSSGenerator;
use ThemeCore\Domain\Port\IThemeRepository;

final class GenerateDynamicCSSUseCaseTest extends TestCase
{
    public function test_generates_css_when_cache_empty(): void
    {
        // Arrange
        $expectedCSS = ':root { --theme-primary: #0D6EFD; }';

        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->expects($this->once())
            ->method('get')
            ->willReturn(ThemeConfig::default());

        $generator = $this->createMock(ICSSGenerator::class);
        $generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($expectedCSS);

        $cache = $this->createMock(ICacheService::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->with('dynamic_css')
            ->willReturn(null);

        $cache
            ->expects($this->once())
            ->method('set')
            ->with('dynamic_css', $expectedCSS, 3600);

        $useCase = new GenerateDynamicCSSUseCase($repository, $generator, $cache);

        // Act
        $result = $useCase->execute();

        // Assert
        self::assertEquals($expectedCSS, $result);
    }

    public function test_returns_cached_css_when_available(): void
    {
        // Arrange
        $cachedCSS = ':root { --theme-primary: #CACHED; }';

        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->expects($this->never()) // Should not call repository when cached
            ->method('get');

        $generator = $this->createMock(ICSSGenerator::class);
        $generator
            ->expects($this->never()) // Should not generate when cached
            ->method('generate');

        $cache = $this->createMock(ICacheService::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->with('dynamic_css')
            ->willReturn($cachedCSS);

        $useCase = new GenerateDynamicCSSUseCase($repository, $generator, $cache);

        // Act
        $result = $useCase->execute();

        // Assert
        self::assertEquals($cachedCSS, $result);
    }

    public function test_regenerates_css_when_cache_empty_string(): void
    {
        // Arrange
        $expectedCSS = ':root { --theme-primary: #0D6EFD; }';

        $repository = $this->createMock(IThemeRepository::class);
        $repository
            ->method('get')
            ->willReturn(ThemeConfig::default());

        $generator = $this->createMock(ICSSGenerator::class);
        $generator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($expectedCSS);

        $cache = $this->createMock(ICacheService::class);
        $cache
            ->method('get')
            ->willReturn(''); // Empty string should trigger regeneration

        $useCase = new GenerateDynamicCSSUseCase($repository, $generator, $cache);

        // Act
        $result = $useCase->execute();

        // Assert
        self::assertEquals($expectedCSS, $result);
    }

    public function test_invalidates_cache(): void
    {
        // Arrange
        $repository = $this->createMock(IThemeRepository::class);
        $generator = $this->createMock(ICSSGenerator::class);

        $cache = $this->createMock(ICacheService::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('dynamic_css');

        $useCase = new GenerateDynamicCSSUseCase($repository, $generator, $cache);

        // Act
        $useCase->invalidateCache();

        // Assert - expectations verified by mock
        $this->assertTrue(true);
    }

    public function test_caches_generated_css_with_correct_ttl(): void
    {
        // Arrange
        $expectedCSS = 'body { font-family: Arial; }';

        $repository = $this->createMock(IThemeRepository::class);
        $repository->method('get')->willReturn(ThemeConfig::default());

        $generator = $this->createMock(ICSSGenerator::class);
        $generator->method('generate')->willReturn($expectedCSS);

        $cache = $this->createMock(ICacheService::class);
        $cache->method('get')->willReturn(null);
        $cache
            ->expects($this->once())
            ->method('set')
            ->with('dynamic_css', $expectedCSS, 3600); // Verify TTL is 1 hour

        $useCase = new GenerateDynamicCSSUseCase($repository, $generator, $cache);

        // Act
        $useCase->execute();

        // Assert - expectations verified by mock
        $this->assertTrue(true);
    }
}
