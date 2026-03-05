<?php

namespace Tests\Unit\Http\Controllers\Help;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Help\HelpController;

class HelpControllerTest extends TestCase
{
    protected $HelpController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->HelpController = new HelpController();
    }

    #[Test]
    public function test_about()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HelpController->about();
    }

    #[Test]
    public function test_menu()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HelpController->menu();
    }

    #[Test]
    public function test_search()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->HelpController->search();
    }

}
