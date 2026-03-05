<?php

namespace Tests\Unit\Http\Controllers\Post;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Post\FileRightsController;

class FileRightsControllerTest extends TestCase
{
    protected $FileRightsController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->FileRightsController = new FileRightsController();
    }

    #[Test]
    public function test_show()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FileRightsController->show();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FileRightsController->update();
    }

}
