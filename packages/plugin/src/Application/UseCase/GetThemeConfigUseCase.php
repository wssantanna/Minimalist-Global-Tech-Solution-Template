<?php

declare(strict_types=1);

namespace ThemeCore\Application\UseCase;

use ThemeCore\Application\DTO\ThemeSettingsDTO;
use ThemeCore\Domain\Port\IThemeRepository;

/**
 * Use Case: Get Theme Configuration
 *
 * Retrieves current theme configuration and converts it to a DTO for presentation layer.
 */
final readonly class GetThemeConfigUseCase
{
    public function __construct(
        private IThemeRepository $repository
    ) {
    }

    /**
     * Execute the use case
     *
     * @return ThemeSettingsDTO Current theme settings
     */
    public function execute(): ThemeSettingsDTO
    {
        $config = $this->repository->get();

        return ThemeSettingsDTO::fromEntity($config);
    }
}
