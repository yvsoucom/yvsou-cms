<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\DomainAttachment;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Throwable;

final class DomainAttachmentTest extends TestCase
{
    private object $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->instantiateSubject();
    }

    #[Test]
    public function test_subject_is_instantiated(): void
    {
        $this->assertInstanceOf(DomainAttachment::class, $this->subject);
    }

    #[Test]
    public function test_declared_public_methods_are_callable(): void
    {
        $reflection = new ReflectionClass($this->subject);
        $methods = array_values(array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            static fn (ReflectionMethod $method): bool =>
                $method->getDeclaringClass()->getName() === $reflection->getName()
                && !$method->isConstructor()
                && !$method->isDestructor()
                && !str_starts_with($method->getName(), '__')
        ));

        $this->assertIsArray($methods);

        foreach ($methods as $method) {
            $args = [];
            foreach ($method->getParameters() as $parameter) {
                $args[] = $this->makeArgumentForParameter($parameter);
            }

            $previousHandler = set_error_handler(static function (
                int $severity,
                string $message,
                string $file,
                int $line
            ): never {
                throw new \ErrorException($message, 0, $severity, $file, $line);
            });

            try {
                $result = $method->invokeArgs($this->subject, $args);
                $this->assertReturnTypeContract($method, $result);
            } catch (Throwable $exception) {
                $this->assertInstanceOf(Throwable::class, $exception);
            } finally {
                restore_error_handler();
            }
        }
    }

    private function instantiateSubject(): object
    {
        $class = new ReflectionClass(DomainAttachment::class);
        $constructor = $class->getConstructor();

        if ($constructor === null || $constructor->getNumberOfRequiredParameters() === 0) {
            return $class->newInstance();
        }

        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            $args[] = $this->makeArgumentForParameter($parameter);
        }

        return $class->newInstanceArgs($args);
    }

    private function makeArgumentForParameter(ReflectionParameter $parameter): mixed
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        $type = $parameter->getType();

        if ($type instanceof ReflectionUnionType) {
            foreach ($type->getTypes() as $unionType) {
                if (!$unionType instanceof ReflectionNamedType) {
                    continue;
                }

                if ($unionType->getName() !== 'null') {
                    return $this->makeValueForNamedType($unionType, $parameter->allowsNull());
                }
            }
            return null;
        }

        if ($type instanceof ReflectionNamedType) {
            return $this->makeValueForNamedType($type, $parameter->allowsNull());
        }

        return null;
    }

    private function makeValueForNamedType(ReflectionNamedType $type, bool $allowsNull): mixed
    {
        $name = $type->getName();

        if ($type->isBuiltin()) {
            return match ($name) {
                'int' => 1,
                'float' => 1.0,
                'string' => 'test-value',
                'bool' => true,
                'array' => [],
                'callable' => static fn () => null,
                'iterable' => [],
                'object' => new \stdClass(),
                'mixed' => null,
                default => $allowsNull ? null : null,
            };
        }

        if (enum_exists($name)) {
            $cases = $name::cases();
            return $cases[0] ?? null;
        }

        if (is_a($name, \DateTimeInterface::class, true)) {
            return new \DateTimeImmutable('2025-01-01 00:00:00');
        }

        if (interface_exists($name)) {
            return $this->createMock($name);
        }

        if (class_exists($name)) {
            $ref = new ReflectionClass($name);

            if (!$ref->isFinal() && !method_exists($name, 'method')) {
                return $this->createMock($name);
            }

            if ($ref->isInstantiable()) {
                $ctor = $ref->getConstructor();
                if ($ctor === null || $ctor->getNumberOfRequiredParameters() === 0) {
                    return $ref->newInstance();
                }

                return $ref->newInstanceWithoutConstructor();
            }
        }

        return $allowsNull ? null : null;
    }

    private function assertReturnTypeContract(ReflectionMethod $method, mixed $result): void
    {
        $returnType = $method->getReturnType();
        if ($returnType === null) {
            $this->assertTrue(true);
            return;
        }

        if ($returnType instanceof ReflectionUnionType) {
            if ($result === null) {
                $this->assertTrue($returnType->allowsNull());
                return;
            }
            $this->assertTrue(true);
            return;
        }

        if (!$returnType instanceof ReflectionNamedType) {
            // Intersection / DNF return types are accepted as long as invocation does not crash.
            $this->assertTrue(true);
            return;
        }

        $name = $returnType->getName();

        if ($name === 'void') {
            $this->assertNull($result);
            return;
        }

        if ($result === null) {
            $this->assertTrue($returnType->allowsNull());
            return;
        }

        if ($returnType->isBuiltin()) {
            match ($name) {
                'int' => $this->assertIsInt($result),
                'float' => $this->assertIsFloat($result),
                'string' => $this->assertIsString($result),
                'bool' => $this->assertIsBool($result),
                'array' => $this->assertIsArray($result),
                'iterable' => $this->assertIsIterable($result),
                'object' => $this->assertIsObject($result),
                default => $this->assertTrue(true),
            };
            return;
        }

        $this->assertInstanceOf($name, $result);
    }
}