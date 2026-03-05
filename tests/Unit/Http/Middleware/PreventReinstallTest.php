<?php

namespace Tests\Unit\Http\Middleware;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Middleware\PreventReinstall;

class PreventReinstallTest extends TestCase
{
    protected $PreventReinstall;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PreventReinstall = new PreventReinstall();
    }

    #[Test]
    public function test_handle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PreventReinstall->handle();
    }

}
