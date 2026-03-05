<?php

namespace Tests\Unit\Providers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Providers\ShortcodeServiceProvider;

class ShortcodeServiceProviderTest extends TestCase
{
    protected $ShortcodeServiceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ShortcodeServiceProvider = new ShortcodeServiceProvider();
    }

    #[Test]
    public function test_register()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeServiceProvider->register();
    }

    #[Test]
    public function test_boot()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ShortcodeServiceProvider->boot();
    }

}
