<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Providers\FilterServiceProvider;

class FilterServiceProviderTest extends TestCase
{
    protected $FilterServiceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->FilterServiceProvider = new FilterServiceProvider();
    }

    #[Test]
    public function test_register()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FilterServiceProvider->register();
    }

    #[Test]
    public function test_boot()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FilterServiceProvider->boot();
    }

}
