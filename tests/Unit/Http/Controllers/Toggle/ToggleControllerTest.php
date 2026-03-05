<?php

namespace Tests\Unit\Http\Controllers\Toggle;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Toggle\ToggleController;

class ToggleControllerTest extends TestCase
{
    protected $ToggleController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ToggleController = new ToggleController();
    }

    #[Test]
    public function test_toggleAlist()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ToggleController->toggleAlist();
    }

}
