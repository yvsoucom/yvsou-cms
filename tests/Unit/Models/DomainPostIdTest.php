<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainPostId;

class DomainPostIdTest extends TestCase
{
    protected $DomainPostId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainPostId = new DomainPostId();
    }

    #[Test]
    public function test_isTrashedFor()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainPostId->isTrashedFor();
    }

}
