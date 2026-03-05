<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Providers\PluginServiceProvider;

class PluginServiceProviderTest extends TestCase
{
    protected $PluginServiceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PluginServiceProvider = new PluginServiceProvider();
    }

    #[Test]
    public function test_register()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PluginServiceProvider->register();
    }

}
