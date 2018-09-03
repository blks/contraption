<?php

namespace Contraption\Tests;

use Contraption\Core\Container\Container;
use Contraption\Core\Container\Dependencies\ClassResolver;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ContainerTest extends TestCase
{
    public function testThrowsNotFoundException(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container = Container::instance();
        $container->get('test');
    }

    public function testThrowContainerException(): void
    {
        $this->expectException(ContainerExceptionInterface::class);

        $container = Container::instance();
        $container->bind(ContainerInterface::class, function () {
            throw new \Exception('Uh-oh');
        });
        $container->get(ContainerInterface::class);
    }

    public function testCanResolveClosures(): void
    {
        $container = Container::instance();
        $container->bind(ContainerInterface::class, function () {
            return Container::instance();
        }, true);

        $resolved = $container->get(ContainerInterface::class);

        $this->assertInstanceOf(ContainerInterface::class, $resolved);
        $this->assertInstanceOf(Container::class, $resolved);
    }

    public function testCanResolveFunctions(): void
    {
        $container = Container::instance();
        $container->bind(\Directory::class, 'dir');

        $resolved = $container->getWith(\Directory::class, [__DIR__]);

        $this->assertInstanceOf(\Directory::class, $resolved);
        $this->assertEquals(__DIR__, $resolved->path);
    }

    public function testCanResolveObjects(): void
    {
        $container = Container::instance();
        $container->bind(ContainerInterface::class, $container);

        $resolved = $container->get(ContainerInterface::class);

        $this->assertInstanceOf(ContainerInterface::class, $resolved);
        $this->assertInstanceOf(Container::class, $resolved);
        $this->assertNotSame($container, $resolved);
    }

    public function testCanResolveSharedObjects(): void
    {
        $container = Container::instance();
        $container->bind(ContainerInterface::class, $container, true);

        $resolved = $container->get(ContainerInterface::class);

        $this->assertInstanceOf(ContainerInterface::class, $resolved);
        $this->assertInstanceOf(Container::class, $resolved);
        $this->assertSame($container, $resolved);
    }

    public function testCanMake(): void
    {
        $container = Container::instance();

        $resolved = $container->make(\stdClass::class);

        $this->assertInstanceOf(\stdClass::class, $resolved);
    }

    public function testCanMakeWith(): void
    {
        $container     = Container::instance();
        $classResolver = $container->makeWith(ClassResolver::class, ['class' => \stdClass::class]);

        $this->assertInstanceOf(ClassResolver::class, $classResolver);
        $this->assertInstanceOf(\stdClass::class, $classResolver->resolve());
    }
}