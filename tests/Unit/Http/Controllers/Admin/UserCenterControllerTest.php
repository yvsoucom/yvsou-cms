<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Admin\UserCenterController;

class UserCenterControllerTest extends TestCase
{
    protected $UserCenterController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UserCenterController = new UserCenterController();
    }

    #[Test]
    public function test_index()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->UserCenterController->index();
    }

}
