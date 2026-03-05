<?php

namespace Tests\Unit\Http\Controllers\Lang;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Lang\LangController;

class LangControllerTest extends TestCase
{
    protected $LangController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->LangController = new LangController();
    }

    #[Test]
    public function test_setLang()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LangController->setLang();
    }

}
