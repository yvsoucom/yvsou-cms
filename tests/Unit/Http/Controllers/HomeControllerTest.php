<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\HomeController;

class HomeControllerTest extends TestCase
{
    protected $HomeController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->HomeController = new HomeController();
    }

    #[Test]
    public function test_home()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HomeController->home();
    }

    #[Test]
    public function test_about()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HomeController->about();
    }

    #[Test]
    public function test_contact()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HomeController->contact();
    }

    #[Test]
    public function test_profile()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HomeController->profile();
    }

    #[Test]
    public function test_terms()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HomeController->terms();
    }

    #[Test]
    public function test_privacy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HomeController->privacy();
    }

}
