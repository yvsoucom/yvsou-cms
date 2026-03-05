<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\RightsService;

class RightsServiceTest extends TestCase
{
    protected $RightsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->RightsService = new RightsService();
    }

    #[Test]
    public function test_getManageDomainOwner()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->getManageDomainOwner();
    }

    #[Test]
    public function test_checkPermition()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkPermition();
    }

    #[Test]
    public function test_checkRightPermission()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkRightPermission();
    }

    #[Test]
    public function test_checkAnyUser()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkAnyUser();
    }

    #[Test]
    public function test_checkOwnerRight()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkOwnerRight();
    }

    #[Test]
    public function test_checkOwnGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkOwnGroup();
    }

    #[Test]
    public function test_checkGrantGroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkGrantGroup();
    }

    #[Test]
    public function test_checkGrantUser()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkGrantUser();
    }

    #[Test]
    public function test_checkCommentRightPermission()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkCommentRightPermission();
    }

    #[Test]
    public function test_check_cotablerightpermision()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->check_cotablerightpermision();
    }

    #[Test]
    public function test_fileAccess()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->fileAccess();
    }

    #[Test]
    public function test_getUploadOwnerId()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->getUploadOwnerId();
    }

    #[Test]
    public function test_attachfileAccess()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->attachfileAccess();
    }

    #[Test]
    public function test_checkFileAccess()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkFileAccess();
    }

    #[Test]
    public function test_unreadcheck()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->unreadcheck();
    }

    #[Test]
    public function test_check_filerightpermision()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->check_filerightpermision();
    }

    #[Test]
    public function test_checkfilepermition()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkfilepermition();
    }

    #[Test]
    public function test_checkfileanyuser()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkfileanyuser();
    }

    #[Test]
    public function test_checkfileownerright()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkfileownerright();
    }

    #[Test]
    public function test_checkfileowngroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkfileowngroup();
    }

    #[Test]
    public function test_checkfilegrantgroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkfilegrantgroup();
    }

    #[Test]
    public function test_checkfilegrantuser()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->RightsService->checkfilegrantuser();
    }

}
