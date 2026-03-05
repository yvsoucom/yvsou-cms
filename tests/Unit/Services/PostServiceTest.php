<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\PostService;

class PostServiceTest extends TestCase
{
    protected $PostService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PostService = new PostService();
    }

    #[Test]
    public function test_getPostTitle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPostTitle();
    }

    #[Test]
    public function test_getPostAuthor()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPostAuthor();
    }

    #[Test]
    public function test_getPostDate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPostDate();
    }

    #[Test]
    public function test_getPostRights()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPostRights();
    }

    #[Test]
    public function test_getComment_rights()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getComment_rights();
    }

    #[Test]
    public function test_getPostFromPostid()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPostFromPostid();
    }

    #[Test]
    public function test_getDomainPostGroups()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getDomainPostGroups();
    }

    #[Test]
    public function test_getAllSubPostGroups()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getAllSubPostGroups();
    }

    #[Test]
    public function test_getPostCounts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPostCounts();
    }

    #[Test]
    public function test_getDomainPostCounts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getDomainPostCounts();
    }

    #[Test]
    public function test_getAllSubPostCounts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getAllSubPostCounts();
    }

    #[Test]
    public function test_getPosts()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostService->getPosts();
    }

}
