<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;

class UserTest extends TestCase
{
    protected $User;

    protected function setUp(): void
    {
        parent::setUp();
        $this->User = new User();
    }

    #[Test]
    public function test_isAdmin()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->isAdmin();
    }

    #[Test]
    public function test_isAuthorOfPost()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->isAuthorOfPost();
    }

    #[Test]
    public function test_withinGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->withinGroup();
    }

    #[Test]
    public function test_hasApplyJoinGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->hasApplyJoinGroup();
    }

    #[Test]
    public function test_withingrantgroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->withingrantgroup();
    }

    #[Test]
    public function test_isPaperOwner()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->isPaperOwner();
    }

    #[Test]
    public function test_canManagePaper()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->canManagePaper();
    }

    #[Test]
    public function test_isGrantUser()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->isGrantUser();
    }

    #[Test]
    public function test_isManageDomainOwner()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->isManageDomainOwner();
    }

    #[Test]
    public function test_withDomainPublicStatus()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->withDomainPublicStatus();
    }

    #[Test]
    public function test_getAliasNameByID()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->getAliasNameByID();
    }

    #[Test]
    public function test_canComment()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->canComment();
    }

    #[Test]
    public function test_canDomainRights()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->canDomainRights();
    }

    #[Test]
    public function test_canUpdateDomainRights()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->User->canUpdateDomainRights();
    }

}
