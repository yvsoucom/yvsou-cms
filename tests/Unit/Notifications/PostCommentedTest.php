<?php

namespace Tests\Unit\Notifications;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Notifications\PostCommented;

class PostCommentedTest extends TestCase
{
    protected $PostCommented;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PostCommented = new PostCommented();
    }

    #[Test]
    public function test_via()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostCommented->via();
    }

    #[Test]
    public function test_toMail()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostCommented->toMail();
    }

}
