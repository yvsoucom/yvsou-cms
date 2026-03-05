<?php

namespace Tests\Unit\View\Components;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\View\Components\AppLayout;

class AppLayoutTest extends TestCase
{
    protected $AppLayout;

    protected function setUp(): void
    {
        parent::setUp();
        $this->AppLayout = new AppLayout();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->AppLayout->render();
    }

}
