<?php

namespace Tests\Unit\Http\Controllers\Post;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Post\BookingController;

class BookingControllerTest extends TestCase
{
    protected $BookingController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->BookingController = new BookingController();
    }

    #[Test]
    public function test_requestFile()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->BookingController->requestFile();
    }

    #[Test]
    public function test_relayUpload()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->BookingController->relayUpload();
    }

    #[Test]
    public function test_download()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->BookingController->download();
    }

}
