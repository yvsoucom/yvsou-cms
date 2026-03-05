<?php

namespace Tests\Unit\Http\Controllers\Error;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Error\ErrorController;

class ErrorControllerTest extends TestCase
{
    protected $ErrorController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ErrorController = new ErrorController();
    }

    #[Test]
    public function test_attachedfile()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ErrorController->attachedfile();
    }

}
