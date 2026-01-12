<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Integration\Adapter;

use PHPUnit\Framework\TestCase;
use ThemeCore\Infrastructure\Adapter\WPTransientCache;

/**
 * @group integration
 * @group wordpress
 */
final class WPTransientCacheTest extends TestCase
{
    private WPTransientCache $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = new WPTransientCache();

        // Clean up before each test
        $this->cache->clear();
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        $this->cache->clear();
        parent::tearDown();
    }

    public function test_get_returns_null_for_non_existent_key(): void
    {
        $value = $this->cache->get('non_existent_key');

        $this->assertNull($value);
    }

    public function test_set_and_get_string_value(): void
    {
        $this->cache->set('test_key', 'test_value');
        $value = $this->cache->get('test_key');

        $this->assertEquals('test_value', $value);
    }

    public function test_set_and_get_array_value(): void
    {
        $data = ['foo' => 'bar', 'baz' => 123];
        $this->cache->set('array_key', $data);
        $value = $this->cache->get('array_key');

        $this->assertEquals($data, $value);
    }

    public function test_set_and_get_object_value(): void
    {
        $obj = (object) ['property' => 'value'];
        $this->cache->set('object_key', $obj);
        $value = $this->cache->get('object_key');

        $this->assertEquals($obj, $value);
    }

    public function test_has_returns_false_for_non_existent_key(): void
    {
        $this->assertFalse($this->cache->has('non_existent_key'));
    }

    public function test_has_returns_true_for_existing_key(): void
    {
        $this->cache->set('existing_key', 'value');

        $this->assertTrue($this->cache->has('existing_key'));
    }

    public function test_delete_removes_value(): void
    {
        $this->cache->set('delete_key', 'value');
        $this->assertTrue($this->cache->has('delete_key'));

        $this->cache->delete('delete_key');

        $this->assertFalse($this->cache->has('delete_key'));
        $this->assertNull($this->cache->get('delete_key'));
    }

    public function test_clear_removes_all_values(): void
    {
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->cache->set('key3', 'value3');

        $this->cache->clear();

        $this->assertFalse($this->cache->has('key1'));
        $this->assertFalse($this->cache->has('key2'));
        $this->assertFalse($this->cache->has('key3'));
    }

    public function test_respects_ttl(): void
    {
        $this->cache->set('ttl_key', 'value', 1); // 1 second TTL

        $this->assertTrue($this->cache->has('ttl_key'));

        // Wait for expiration
        sleep(2);

        $this->assertFalse($this->cache->has('ttl_key'));
        $this->assertNull($this->cache->get('ttl_key'));
    }

    public function test_set_with_zero_value(): void
    {
        $this->cache->set('zero_key', 0);
        $value = $this->cache->get('zero_key');

        $this->assertEquals(0, $value);
    }

    public function test_set_with_false_value(): void
    {
        $this->cache->set('false_key', false);
        $value = $this->cache->get('false_key');

        $this->assertFalse($value);
        $this->assertTrue($this->cache->has('false_key'));
    }

    public function test_set_with_empty_string(): void
    {
        $this->cache->set('empty_key', '');
        $value = $this->cache->get('empty_key');

        $this->assertEquals('', $value);
        $this->assertTrue($this->cache->has('empty_key'));
    }
}
