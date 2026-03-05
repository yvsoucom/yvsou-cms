<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\CommentService;

class CommentServiceTest extends TestCase
{
    protected $CommentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CommentService = new CommentService();
    }

    #[Test]
    public function test_getCommentNumbers()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentService->getCommentNumbers();
    }

    #[Test]
    public function test_isCommentAuthor()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentService->isCommentAuthor();
    }

    #[Test]
    public function test_isParentComment()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentService->isParentComment();
    }

    #[Test]
    public function test_getChildrenComments()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentService->getChildrenComments();
    }

}
