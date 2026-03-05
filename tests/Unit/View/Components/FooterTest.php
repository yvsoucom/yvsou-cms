<?php

namespace Tests\Unit\View\Components;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\View\Components\Footer;

class FooterTest extends TestCase
{
    protected $Footer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Footer = new Footer();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->Footer->render();
    }

}
