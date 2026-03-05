<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\LocaleService;

class LocaleServiceTest extends TestCase
{
    protected $LocaleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->LocaleService = new LocaleService();
    }

    #[Test]
    public function test_setbootLocaleFromCookie()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LocaleService->setbootLocaleFromCookie();
    }

    #[Test]
    public function test_getlangSet()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LocaleService->getlangSet();
    }

    #[Test]
    public function test_getlangIdSet()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LocaleService->getlangIdSet();
    }

    #[Test]
    public function test_getlangID()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LocaleService->getlangID();
    }

    #[Test]
    public function test_getcurlang()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LocaleService->getcurlang();
    }

    #[Test]
    public function test_getcurlangcode()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->LocaleService->getcurlangcode();
    }

}
