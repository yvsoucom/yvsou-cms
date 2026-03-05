<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\InstallController;

class InstallControllerTest extends TestCase
{
    protected $InstallController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->InstallController = new InstallController();
    }

    #[Test]
    public function test_fixPermissions()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->fixPermissions();
    }

    #[Test]
    public function test_welcome()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->welcome();
    }

    #[Test]
    public function test_envForm()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->envForm();
    }

    #[Test]
    public function test_reloadDatabaseFromEnv()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->reloadDatabaseFromEnv();
    }

    #[Test]
    public function test_generateAppKey()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->generateAppKey();
    }

    #[Test]
    public function test_saveAdmin()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->saveAdmin();
    }

    #[Test]
    public function test_saveEnv()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->saveEnv();
    }

    #[Test]
    public function test_insert_admin()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->insert_admin();
    }

    #[Test]
    public function test_showMigrate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->showMigrate();
    }

    #[Test]
    public function test_step3()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->step3();
    }

    #[Test]
    public function test_runMigrate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->runMigrate();
    }

    #[Test]
    public function test_ClearCache()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->ClearCache();
    }

    #[Test]
    public function test_done()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->InstallController->done();
    }

}
