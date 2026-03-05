<?php

namespace Tests\Unit\Http\Middleware;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Middleware\SetLocale;

class SetLocaleTest extends TestCase
{
    protected $SetLocale;

    protected function setUp(): void
    {
        parent::setUp();
        $this->SetLocale = new SetLocale();
    }

    #[Test]
    public function test_handle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SetLocale->handle();
    }

}
