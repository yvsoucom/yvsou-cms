<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Providers\AppServiceProvider;

class AppServiceProviderTest extends TestCase
{
    protected $AppServiceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->AppServiceProvider = new AppServiceProvider();
    }

    #[Test]
    public function test_boot()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AppServiceProvider->boot();
    }

}
