<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainComment;

class DomainCommentTest extends TestCase
{
    protected $DomainComment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainComment = new DomainComment();
    }

    #[Test]
    public function test_makecoment()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainComment->makecoment();
    }

    #[Test]
    public function test_user()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainComment->user();
    }

    #[Test]
    public function test_children()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainComment->children();
    }

}
