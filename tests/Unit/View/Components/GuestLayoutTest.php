<?php

namespace Tests\Unit\View\Components;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\View\Components\GuestLayout;

class GuestLayoutTest extends TestCase
{
    protected $GuestLayout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->GuestLayout = new GuestLayout();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GuestLayout->render();
    }

}
