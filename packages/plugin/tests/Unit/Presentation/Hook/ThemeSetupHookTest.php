<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Presentation\Hook;

use PHPUnit\Framework\TestCase;
use ThemeCore\Presentation\Hook\ThemeSetupHook;

final class ThemeSetupHookTest extends TestCase
{
    private ThemeSetupHook $hook;

    protected function setUp(): void
    {
        $this->hook = new ThemeSetupHook();
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

    public function test_has_setup_method(): void
    {
        // Assert
        self::assertTrue(method_exists($this->hook, 'setup'));
    }
}
