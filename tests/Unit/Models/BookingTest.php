<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Booking;

class BookingTest extends TestCase
{
    protected $Booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Booking = new Booking();
    }

    #[Test]
    public function test_post()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->Booking->post();
    }

    #[Test]
    public function test_requester()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->Booking->requester();
    }

}
