<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\DomainViewController;

class DomainViewControllerTest extends TestCase
{
    protected $DomainViewController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainViewController = new DomainViewController();
    }

    #[Test]
    public function test_showSubDomains()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->showSubDomains();
    }

    #[Test]
    public function test_index()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->index();
    }

    #[Test]
    public function test_createsub()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->createsub();
    }

    #[Test]
    public function test_storesub()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->storesub();
    }

    #[Test]
    public function test_editdomain()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->editdomain();
    }

    #[Test]
    public function test_editsub()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->editsub();
    }

    #[Test]
    public function test_updatedomain()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->updatedomain();
    }

    #[Test]
    public function test_trash()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->trash();
    }

    #[Test]
    public function test_untrash()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->untrash();
    }

    #[Test]
    public function test_destroy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->destroy();
    }

    #[Test]
    public function test_auditcheck()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->auditcheck();
    }

    #[Test]
    public function test_audituncheck()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->audituncheck();
    }

    #[Test]
    public function test_rightsshow()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->rightsshow();
    }

    #[Test]
    public function test_rightsupdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainViewController->rightsupdate();
    }

}
