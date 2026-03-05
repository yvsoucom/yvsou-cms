<?php

namespace Tests\Unit\Notifications;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Notifications\CommentReplied;

class CommentRepliedTest extends TestCase
{
    protected $CommentReplied;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CommentReplied = new CommentReplied();
    }

    #[Test]
    public function test_via()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentReplied->via();
    }

    #[Test]
    public function test_toMail()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentReplied->toMail();
    }

}
