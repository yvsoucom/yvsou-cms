<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\Controller;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

final class ControllerTest extends TestCase
{
    #[Test]
    public function test_controller_is_abstract_base_type(): void
    {
        $reflection = new ReflectionClass(Controller::class);
        $this->assertTrue($reflection->isAbstract());
    }

    #[Test]
    public function test_controller_declares_no_public_api_methods(): void
    {
        $reflection = new ReflectionClass(Controller::class);
        $methods = array_values(array_filter(
            $reflection->getMethods(ReflectionMethod::IS_PUBLIC),
            static fn (ReflectionMethod $method): bool =>
                $method->getDeclaringClass()->getName() === $reflection->getName()
        ));

        $this->assertCount(0, $methods);
    }
}
