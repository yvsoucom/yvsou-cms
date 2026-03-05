<?php

namespace Tests\Unit\Http\Controllers\Admin;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Admin\UpdaterController;

class UpdaterControllerTest extends TestCase
{
    protected $UpdaterController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UpdaterController = new UpdaterController();
    }

    #[Test]
    public function test_run()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->UpdaterController->run();
    }

}
