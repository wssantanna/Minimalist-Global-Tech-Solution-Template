<?php

declare(strict_types=1);

namespace ThemeCore\Domain\Port;

interface ICacheService
{
    /**
     * Get value from cache
     *
     * @return mixed|null
     */
    public function get(string $key): mixed;

    /**
     * Set value in cache
     *
     * @param mixed $value
     */
    public function set(string $key, mixed $value, int $ttl = 3600): void;

    /**
     * Check if key exists in cache
     */
    public function has(string $key): bool;

    /**
     * Delete value from cache
     */
    public function delete(string $key): void;

    /**
     * Clear all cache
     */
    public function clear(): void;
}
