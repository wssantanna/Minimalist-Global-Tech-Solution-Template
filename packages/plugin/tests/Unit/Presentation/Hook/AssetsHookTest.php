<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Presentation\Hook;

use PHPUnit\Framework\TestCase;
use ThemeCore\Presentation\Hook\AssetsHook;

final class AssetsHookTest extends TestCase
{
    private AssetsHook $hook;

    protected function setUp(): void
    {
        $this->hook = new AssetsHook();
    }

    public function test_implements_hook_interface(): void
    {
        // Assert
        self::assertInstanceOf(
            \ThemeCore\Presentation\Hook\HookInterface::class,
            $this->hook
        );
    }

    public function test_has_register_method(): void
    {
        // Assert
        self::assertTrue(method_exists($this->hook, 'register'));
    }

    public function test_has_enqueue_styles_method(): void
    {
        // Assert
        self::assertTrue(method_exists($this->hook, 'enqueueStyles'));
    }

    public function test_has_output_dynamic_css_method(): void
    {
        // Assert
        self::assertTrue(method_exists($this->hook, 'outputDynamicCSS'));
    }

    public function test_can_be_constructed_with_css_callback(): void
    {
        // Arrange
        $callback = fn() => 'body { color: red; }';

        // Act
        $hook = new AssetsHook($callback);

        // Assert
        self::assertInstanceOf(AssetsHook::class, $hook);
    }

    public function test_can_be_constructed_without_callback(): void
    {
        // Act
        $hook = new AssetsHook();

        // Assert
        self::assertInstanceOf(AssetsHook::class, $hook);
    }
}
