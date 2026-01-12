<?php

declare(strict_types=1);

namespace ThemeCore\Application\UseCase;

use ThemeCore\Domain\Port\ICacheService;
use ThemeCore\Domain\Port\ICSSGenerator;
use ThemeCore\Domain\Port\IThemeRepository;

/**
 * Use Case: Generate Dynamic CSS
 *
 * Generates dynamic CSS from theme configuration with caching support.
 */
final readonly class GenerateDynamicCSSUseCase
{
    private const CACHE_KEY = 'dynamic_css';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private IThemeRepository $repository,
        private ICSSGenerator $generator,
        private ICacheService $cache
    ) {
    }

    /**
     * Execute the use case
     *
     * @return string Generated CSS
     */
    public function execute(): string
    {
        // Try to get from cache first
        $cached = $this->cache->get(self::CACHE_KEY);

        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        // Generate fresh CSS
        $config = $this->repository->get();
        $css = $this->generator->generate($config);

        // Store in cache
        $this->cache->set(self::CACHE_KEY, $css, self::CACHE_TTL);

        return $css;
    }

    /**
     * Invalidate the CSS cache
     *
     * Should be called when theme configuration changes.
     */
    public function invalidateCache(): void
    {
        $this->cache->delete(self::CACHE_KEY);
    }
}
