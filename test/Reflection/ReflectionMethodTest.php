<?php

/**
 * @see       https://github.com/laminas/laminas-server for the canonical source repository
 * @copyright https://github.com/laminas/laminas-server/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-server/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Server\Reflection;

use Laminas\Server\Reflection;
use Laminas\Server\Reflection\Node;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ReflectionMethodTest extends TestCase
{
    /** @var ReflectionClass */
    protected $classRaw;

    /** @var Reflection\ReflectionClass */
    protected $class;

    /** @var \ReflectionMethod */
    protected $method;

    protected function setUp(): void
    {
        $this->classRaw = new ReflectionClass(Reflection::class);
        $this->method   = $this->classRaw->getMethod('reflectClass');
        $this->class    = new Reflection\ReflectionClass($this->classRaw);
    }

    /**
     * __construct() test
     *
     * Call as method call
     *
     * Expects:
     * - class:
     * - r:
     * - namespace: Optional;
     * - argv: Optional; has default;
     *
     * Returns: void
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $r = new Reflection\ReflectionMethod($this->class, $this->method);
        $this->assertEquals('', $r->getNamespace());

        $r = new Reflection\ReflectionMethod($this->class, $this->method, 'namespace');
        $this->assertEquals('namespace', $r->getNamespace());
    }

    /**
     * getDeclaringClass() test
     *
     * Call as method call
     *
     * Returns: \Laminas\Server\Reflection\ReflectionClass
     *
     * @return void
     */
    public function testGetDeclaringClass(): void
    {
        $r = new Reflection\ReflectionMethod($this->class, $this->method);

        $class = $r->getDeclaringClass();

        $this->assertInstanceOf(\Laminas\Server\Reflection\ReflectionClass::class, $class);
        $this->assertEquals($this->class, $class);
    }

    /**
     * __wakeup() test
     *
     * Call as method call
     *
     * Returns: void
     *
     * @return void
     */
    public function testClassWakeup(): void
    {
        $r = new Reflection\ReflectionMethod($this->class, $this->method);
        $s = serialize($r);
        $u = unserialize($s);

        $this->assertInstanceOf(Reflection\ReflectionMethod::class, $u);
        $this->assertEquals($r->getName(), $u->getName());
        $this->assertEquals($r->getDeclaringClass()->getName(), $u->getDeclaringClass()->getName());
    }

    /**
     * Test fetch method doc block from interface
     *
     * @return void
     */
    public function testMethodDocBlockFromInterface(): void
    {
        $reflectionClass = new ReflectionClass(TestAsset\ReflectionMethodTestInstance::class);
        $reflectionMethod = $reflectionClass->getMethod('testMethod');

        $laminasReflectionMethod = new Reflection\ReflectionMethod(
            new Reflection\ReflectionClass($reflectionClass),
            $reflectionMethod
        );
        list($prototype) = $laminasReflectionMethod->getPrototypes();
        list($first, $second) = $prototype->getParameters();

        self::assertEquals('ReflectionMethodTest', $first->getType());
        self::assertEquals('array', $second->getType());
    }

    /**
     * Test fetch method doc block from parent class
     *
     * @return void
     */
    public function testMethodDocBlockFromParent(): void
    {
        $reflectionClass = new ReflectionClass(TestAsset\ReflectionMethodNode::class);
        $reflectionMethod = $reflectionClass->getMethod('setParent');

        $laminasReflectionMethod = new Reflection\ReflectionMethod(
            new Reflection\ReflectionClass($reflectionClass),
            $reflectionMethod
        );
        $prototypes = $laminasReflectionMethod->getPrototypes();
        list($first, $second) = $prototypes[1]->getParameters();

        self::assertEquals('\\' . Node::class, $first->getType());
        self::assertEquals('bool', $second->getType());
    }
}
