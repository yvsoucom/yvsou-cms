<?php

namespace Tests\Unit\View\Components;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\View\Components\Header;

class HeaderTest extends TestCase
{
    protected $Header;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Header = new Header();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->Header->render();
    }

}
