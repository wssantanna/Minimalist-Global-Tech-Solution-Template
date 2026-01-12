<?php

declare(strict_types=1);

namespace ThemeCore\Infrastructure\Adapter;

use ThemeCore\Domain\Port\ICacheService;

final class WPTransientCache implements ICacheService
{
    private const PREFIX = 'theme_core_';

    public function get(string $key): mixed
    {
        $value = get_transient($this->prefixKey($key));

        // WordPress returns false for non-existent transients
        return $value === false ? null : $value;
    }

    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        set_transient($this->prefixKey($key), $value, $ttl);
    }

    public function has(string $key): bool
    {
        return get_transient($this->prefixKey($key)) !== false;
    }

    public function delete(string $key): void
    {
        delete_transient($this->prefixKey($key));
    }

    public function clear(): void
    {
        global $wpdb;

        // Delete all transients with our prefix
        $pattern = $wpdb->esc_like('_transient_' . self::PREFIX) . '%';

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                $pattern
            )
        );

        // Also delete timeout entries
        $timeoutPattern = $wpdb->esc_like('_transient_timeout_' . self::PREFIX) . '%';

        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                $timeoutPattern
            )
        );
    }

    private function prefixKey(string $key): string
    {
        return self::PREFIX . $key;
    }
}
