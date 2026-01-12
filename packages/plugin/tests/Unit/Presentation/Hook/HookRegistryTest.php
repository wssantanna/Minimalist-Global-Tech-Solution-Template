<?php

declare(strict_types=1);

namespace ThemeCore\Tests\Unit\Presentation\Hook;

use PHPUnit\Framework\TestCase;
use ThemeCore\Presentation\Hook\HookInterface;
use ThemeCore\Presentation\Hook\HookRegistry;

final class HookRegistryTest extends TestCase
{
    public function test_adds_hook_to_registry(): void
    {
        // Arrange
        $registry = new HookRegistry();
        $hook = $this->createMock(HookInterface::class);

        // Act
        $result = $registry->add($hook);

        // Assert
        self::assertSame($registry, $result);
        self::assertCount(1, $registry->getHooks());
    }

    public function test_registers_all_hooks(): void
    {
        // Arrange
        $registry = new HookRegistry();

        $hook1 = $this->createMock(HookInterface::class);
        $hook1->expects($this->once())->method('register');

        $hook2 = $this->createMock(HookInterface::class);
        $hook2->expects($this->once())->method('register');

        $registry->add($hook1);
        $registry->add($hook2);

        // Act
        $registry->registerAll();

        // Assert - expectations verified by mocks
        $this->assertTrue(true);
    }

    public function test_clears_all_hooks(): void
    {
        // Arrange
        $registry = new HookRegistry();
        $hook = $this->createMock(HookInterface::class);
        $registry->add($hook);

        // Act
        $registry->clear();

        // Assert
        self::assertCount(0, $registry->getHooks());
    }

    public function test_can_chain_add_calls(): void
    {
        // Arrange
        $registry = new HookRegistry();
        $hook1 = $this->createMock(HookInterface::class);
        $hook2 = $this->createMock(HookInterface::class);
        $hook3 = $this->createMock(HookInterface::class);

        // Act
        $registry
            ->add($hook1)
            ->add($hook2)
            ->add($hook3);

        // Assert
        self::assertCount(3, $registry->getHooks());
    }
}
