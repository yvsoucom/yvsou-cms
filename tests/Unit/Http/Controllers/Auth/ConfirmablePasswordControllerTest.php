<?php

namespace Tests\Unit\Http\Controllers\Auth;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Auth\ConfirmablePasswordController;

class ConfirmablePasswordControllerTest extends TestCase
{
    protected $ConfirmablePasswordController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ConfirmablePasswordController = new ConfirmablePasswordController();
    }

    #[Test]
    public function test_show()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ConfirmablePasswordController->show();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->ConfirmablePasswordController->store();
    }

}
