<?php

namespace Tests\Unit\Theme;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Theme\ThemeServiceProvider;

class ThemeServiceProviderTest extends TestCase
{
    protected $ThemeServiceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ThemeServiceProvider = new ThemeServiceProvider();
    }

    #[Test]
    public function test_register()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ThemeServiceProvider->register();
    }

    #[Test]
    public function test_boot()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ThemeServiceProvider->boot();
    }

}
