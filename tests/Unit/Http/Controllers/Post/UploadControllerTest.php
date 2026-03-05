<?php

namespace Tests\Unit\Http\Controllers\Post;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Post\UploadController;

class UploadControllerTest extends TestCase
{
    protected $UploadController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UploadController = new UploadController();
    }

    #[Test]
    public function test_upload()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->UploadController->upload();
    }

    #[Test]
    public function test_processUpload()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->UploadController->processUpload();
    }

}
