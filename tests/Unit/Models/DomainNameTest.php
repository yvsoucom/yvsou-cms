<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainName;

class DomainNameTest extends TestCase
{
    protected $DomainName;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainName = new DomainName();
    }

    #[Test]
    public function test_getJoinMembers()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->getJoinMembers();
    }

    #[Test]
    public function test_getJoinUsers()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->getJoinUsers();
    }

    #[Test]
    public function test_getNeedApproveMembers()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->getNeedApproveMembers();
    }

    #[Test]
    public function test_getApplyUsers()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->getApplyUsers();
    }

    #[Test]
    public function test_countJoinGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->countJoinGroup();
    }

    #[Test]
    public function test_countRequestedGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->countRequestedGroup();
    }

    #[Test]
    public function test_countBlockGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->countBlockGroup();
    }

    #[Test]
    public function test_getJoinStatus()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->getJoinStatus();
    }

    #[Test]
    public function test_joinGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->joinGroup();
    }

    #[Test]
    public function test_quitGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->quitGroup();
    }

    #[Test]
    public function test_approveGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainName->approveGroup();
    }

}
