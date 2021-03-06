<?php

/**
 * @see       https://github.com/laminas/laminas-server for the canonical source repository
 * @copyright https://github.com/laminas/laminas-server/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-server/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Server;

use Laminas\Server\Reflection;
use Laminas\Server\Reflection\Exception\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @group      Laminas_Server
 */
class ReflectionTest extends TestCase
{
    /**
     * reflectClass() test
     *
     * @return void
     */
    public function testReflectClass(): void
    {
        $reflection = Reflection::reflectClass(TestAsset\ReflectionTestClass::class);
        $this->assertSame(TestAsset\ReflectionTestClass::class, $reflection->getName());
    }

    public function testReflectClassThrowsExceptionOnInvalidClass(): void
    {
        $this->expectException(Reflection\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argv argument passed to reflectClass');
        Reflection::reflectClass(TestAsset\ReflectionTestClass::class, 'string');
    }

    public function testReflectClassThrowsExceptionOnInvalidParameter(): void
    {
        $this->expectException(Reflection\Exception\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid class or object passed to attachClass');
        Reflection::reflectClass(false);
    }

    /**
     * reflectClass() test; test namespaces
     *
     * @return void
     */
    public function testReflectClass2(): void
    {
        $reflection = Reflection::reflectClass(TestAsset\ReflectionTestClass::class, false, 'zsr');
        $this->assertEquals('zsr', $reflection->getNamespace());
    }

    /**
     * reflectFunction() test
     *
     * @return void
     */
    public function testReflectFunction(): void
    {
        $reflection = Reflection::reflectFunction('LaminasTest\Server\TestAsset\reflectionTestFunction');
        $this->assertSame('LaminasTest\Server\TestAsset\reflectionTestFunction', $reflection->getName());
    }

    public function testReflectFunctionThrowsExceptionOnInvalidFunction(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid function');
        Reflection::reflectFunction(TestAsset\ReflectionTestClass::class, 'string');
    }

    public function testReflectFunctionThrowsExceptionOnInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid function');
        Reflection::reflectFunction(false);
    }

    /**
     * reflectFunction() test; test namespaces
     *
     * @return void
     */
    public function testReflectFunction2(): void
    {
        $reflection = Reflection::reflectFunction('LaminasTest\Server\TestAsset\reflectionTestFunction', false, 'zsr');
        $this->assertEquals('zsr', $reflection->getNamespace());
    }
}
