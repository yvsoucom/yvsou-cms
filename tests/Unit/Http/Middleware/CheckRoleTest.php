<?php

namespace Tests\Unit\Http\Middleware;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Middleware\CheckRole;

class CheckRoleTest extends TestCase
{
    protected $CheckRole;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CheckRole = new CheckRole();
    }

    #[Test]
    public function test_handle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CheckRole->handle();
    }

}
