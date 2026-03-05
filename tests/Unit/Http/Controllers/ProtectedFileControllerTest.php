<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\ProtectedFileController;

class ProtectedFileControllerTest extends TestCase
{
    protected $ProtectedFileController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ProtectedFileController = new ProtectedFileController();
    }

    #[Test]
    public function test_show()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ProtectedFileController->show();
    }

}
