<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainManager;

class DomainManagerTest extends TestCase
{
    protected $DomainManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainManager = new DomainManager();
    }

    #[Test]
    public function test_getFirstGroupid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainManager->getFirstGroupid();
    }

    #[Test]
    public function test_setPublic()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainManager->setPublic();
    }

    #[Test]
    public function test_setPrivate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainManager->setPrivate();
    }

    #[Test]
    public function test_updateRight()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainManager->updateRight();
    }

}
