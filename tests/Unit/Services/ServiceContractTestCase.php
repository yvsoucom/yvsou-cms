<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

abstract class ServiceContractTestCase extends TestCase
{
    private ReflectionClass $serviceReflection;

    abstract protected function serviceClass(): string;

    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceReflection = new ReflectionClass($this->serviceClass());
    }

    #[Test]
    public function test_service_class_is_concrete(): void
    {
        $this->assertTrue(class_exists($this->serviceClass()));
        $this->assertFalse($this->serviceReflection->isAbstract());
        $this->assertTrue($this->serviceReflection->isInstantiable());
    }

    #[Test]
    public function test_declared_public_methods_have_unique_names(): void
    {
        $methods = $this->declaredPublicMethods();
        $methodNames = array_map(
            static fn (ReflectionMethod $method): string => $method->getName(),
            $methods
        );

        $this->assertSame($methodNames, array_values(array_unique($methodNames)));
    }

    #[Test]
    public function test_declared_public_method_signatures_are_valid(): void
    {
        $methods = $this->declaredPublicMethods();

        if ($methods === []) {
            $this->addToAssertionCount(1);
            return;
        }

        foreach ($methods as $method) {
            $this->assertGreaterThan(0, strlen($method->getName()));
            $this->assertGreaterThanOrEqual(0, $method->getNumberOfRequiredParameters());
            $this->assertLessThanOrEqual($method->getNumberOfParameters(), $method->getNumberOfRequiredParameters());

            foreach ($method->getParameters() as $parameter) {
                $this->assertGreaterThan(0, strlen($parameter->getName()));
            }
        }
    }

    /** @return list<ReflectionMethod> */
    private function declaredPublicMethods(): array
    {
        $methods = $this->serviceReflection->getMethods(ReflectionMethod::IS_PUBLIC);

        return array_values(array_filter(
            $methods,
            fn (ReflectionMethod $method): bool =>
                $method->getDeclaringClass()->getName() === $this->serviceClass()
                && !$method->isConstructor()
                && !$method->isDestructor()
                && !str_starts_with($method->getName(), '__')
        ));
    }
}
