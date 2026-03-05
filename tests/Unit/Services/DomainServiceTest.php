<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\DomainService;

class DomainServiceTest extends TestCase
{
    protected $DomainService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainService = new DomainService();
    }

    #[Test]
    public function test_get_children_by_groupid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_children_by_groupid();
    }

    #[Test]
    public function test_get_title_by_id()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_title_by_id();
    }

    #[Test]
    public function test_get_first_title_by_id()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_first_title_by_id();
    }

    #[Test]
    public function test_get_titledescription_by_id()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_titledescription_by_id();
    }

    #[Test]
    public function test_get_first_titledescription_by_id()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_first_titledescription_by_id();
    }

    #[Test]
    public function test_get_jointitle_by_id()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_jointitle_by_id();
    }

    #[Test]
    public function test_get_jointitledescription_by_id()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_jointitledescription_by_id();
    }

    #[Test]
    public function test_get_jointitle_by_uniqid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_jointitle_by_uniqid();
    }

    #[Test]
    public function test_get_joinGrouptitle_by_uniqid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_joinGrouptitle_by_uniqid();
    }

    #[Test]
    public function test_get_joinGroupLink_by_uniqid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_joinGroupLink_by_uniqid();
    }

    #[Test]
    public function test_get_joinLink_by_uniqid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_joinLink_by_uniqid();
    }

    #[Test]
    public function test_get_joinGrouptitle_by_uniqidTwo()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_joinGrouptitle_by_uniqidTwo();
    }

    #[Test]
    public function test_is_domain_leaf()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->is_domain_leaf();
    }

    #[Test]
    public function test_get_id_from_groupid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_id_from_groupid();
    }

    #[Test]
    public function test_get_topid_from_groupid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->get_topid_from_groupid();
    }

    #[Test]
    public function test_insertDomainTree()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->insertDomainTree();
    }

    #[Test]
    public function test_updateDomainTree()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->updateDomainTree();
    }

    #[Test]
    public function test_checkDomainPublicStatus()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainService->checkDomainPublicStatus();
    }

}
