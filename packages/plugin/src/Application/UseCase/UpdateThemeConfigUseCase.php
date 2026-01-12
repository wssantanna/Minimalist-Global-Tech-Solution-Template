<?php

declare(strict_types=1);

namespace ThemeCore\Application\UseCase;

use ThemeCore\Application\DTO\UpdateThemeSettingsDTO;
use ThemeCore\Domain\Port\ICacheService;
use ThemeCore\Domain\Port\IThemeRepository;

/**
 * Use Case: Update Theme Configuration
 *
 * Updates theme configuration and invalidates related caches.
 */
final readonly class UpdateThemeConfigUseCase
{
    public function __construct(
        private IThemeRepository $repository,
        private ICacheService $cache
    ) {
    }

    /**
     * Execute the use case
     *
     * @param UpdateThemeSettingsDTO $settings New settings to apply
     * @throws \InvalidArgumentException If validation fails
     */
    public function execute(UpdateThemeSettingsDTO $settings): void
    {
        // Convert DTO to Domain Entity (validation happens here)
        $config = $settings->toEntity();

        // Persist configuration
        $this->repository->save($config);

        // Invalidate CSS cache since configuration changed
        $this->cache->delete('dynamic_css');
    }
}
