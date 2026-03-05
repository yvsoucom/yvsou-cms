<?php

namespace Tests\Unit\Http\Controllers\Post;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Post\CommentRightsController;

class CommentRightsControllerTest extends TestCase
{
    protected $CommentRightsController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CommentRightsController = new CommentRightsController();
    }

    #[Test]
    public function test_edit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentRightsController->edit();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CommentRightsController->update();
    }

}
