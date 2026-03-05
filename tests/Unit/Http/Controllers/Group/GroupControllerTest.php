<?php

namespace Tests\Unit\Http\Controllers\Group;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Group\GroupController;

class GroupControllerTest extends TestCase
{
    protected $GroupController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->GroupController = new GroupController();
    }

    #[Test]
    public function test_joingroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->joingroup();
    }

    #[Test]
    public function test_quitgroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->quitgroup();
    }

    #[Test]
    public function test_approvegroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->approvegroup();
    }

    #[Test]
    public function test_storeapprove()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->storeapprove();
    }

    #[Test]
    public function test_sendMessage2Users()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->sendMessage2Users();
    }

    #[Test]
    public function test_groupmessage()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->groupmessage();
    }

    #[Test]
    public function test_editmessage()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->editmessage();
    }

    #[Test]
    public function test_castmessagestore()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->castmessagestore();
    }

    #[Test]
    public function test_messagestore()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->messagestore();
    }

    #[Test]
    public function test_invitegroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->invitegroup();
    }

    #[Test]
    public function test_auditcheckgroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->auditcheckgroup();
    }

    #[Test]
    public function test_unauditcheckgroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->unauditcheckgroup();
    }

    #[Test]
    public function test_setpublic()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->setpublic();
    }

    #[Test]
    public function test_setprivate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->GroupController->setprivate();
    }

}
