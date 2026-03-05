<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Admin\CastMsgController;

class CastMsgControllerTest extends TestCase
{
    protected $CastMsgController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CastMsgController = new CastMsgController();
    }

    #[Test]
    public function test_edit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CastMsgController->edit();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CastMsgController->update();
    }

}
