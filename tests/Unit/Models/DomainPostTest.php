<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainPost;

class DomainPostTest extends TestCase
{
    protected $DomainPost;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainPost = new DomainPost();
    }

    #[Test]
    public function test_postgroups()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainPost->postgroups();
    }

    #[Test]
    public function test_modifiedBy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainPost->modifiedBy();
    }

    #[Test]
    public function test_reversions()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainPost->reversions();
    }

}
