<?php

namespace Tests\Unit\View\Components\Install;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\View\Components\Install\Input;

class InputTest extends TestCase
{
    protected $Input;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Input = new Input();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->Input->render();
    }

}
