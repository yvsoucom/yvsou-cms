<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainTree;

class DomainTreeTest extends TestCase
{
    protected $DomainTree;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainTree = new DomainTree();
    }

    #[Test]
    public function test_dict()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainTree->dict();
    }

    #[Test]
    public function test_getProperties()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainTree->getProperties();
    }

}
