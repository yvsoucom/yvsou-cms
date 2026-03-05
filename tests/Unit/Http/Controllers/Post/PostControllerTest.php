<?php

namespace Tests\Unit\Http\Controllers\Post;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Post\PostController;

class PostControllerTest extends TestCase
{
    protected $PostController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PostController = new PostController();
    }

    #[Test]
    public function test_convertImageUrlsToRelative()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->convertImageUrlsToRelative();
    }

    #[Test]
    public function test_convertHrefToRelative()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->convertHrefToRelative();
    }

    #[Test]
    public function test_convertToMigrateRelative()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->convertToMigrateRelative();
    }

    #[Test]
    public function test_removeMigarateContentUrls()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->removeMigarateContentUrls();
    }

    #[Test]
    public function test_convertImageSrcToRelative()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->convertImageSrcToRelative();
    }

    #[Test]
    public function test_addProtectedUrls()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->addProtectedUrls();
    }

    #[Test]
    public function test_convertToProtectedUrls()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->convertToProtectedUrls();
    }

    #[Test]
    public function test_showComments()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->showComments();
    }

    #[Test]
    public function test_isAtatchSamewithPost()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->isAtatchSamewithPost();
    }

    #[Test]
    public function test_index()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->index();
    }

    #[Test]
    public function test_postview()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->postview();
    }

    #[Test]
    public function test_replaceWithRelativeUrls()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->replaceWithRelativeUrls();
    }

    #[Test]
    public function test_commentstore()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->commentstore();
    }

    #[Test]
    public function test_create()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->create();
    }

    #[Test]
    public function test_localcreate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->localcreate();
    }

    #[Test]
    public function test_store()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->store();
    }

    #[Test]
    public function test_edit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->edit();
    }

    #[Test]
    public function test_localedit()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->localedit();
    }

    #[Test]
    public function test_update()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->update();
    }

    #[Test]
    public function test_reversionsJson()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->reversionsJson();
    }

    #[Test]
    public function test_restoreUpdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->restoreUpdate();
    }

    #[Test]
    public function test_restorereversion()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->restorereversion();
    }

    #[Test]
    public function test_trash()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->trash();
    }

    #[Test]
    public function test_untrash()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->untrash();
    }

    #[Test]
    public function test_destroy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->destroy();
    }

    #[Test]
    public function test_auditcheck()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->auditcheck();
    }

    #[Test]
    public function test_audituncheck()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->audituncheck();
    }

    #[Test]
    public function test_movegroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->movegroup();
    }

    #[Test]
    public function test_copygroup()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->copygroup();
    }

    #[Test]
    public function test_movelang()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->movelang();
    }

    #[Test]
    public function test_movegroupupdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->movegroupupdate();
    }

    #[Test]
    public function test_copygroupupdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->copygroupupdate();
    }

    #[Test]
    public function test_movelangupdate()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostController->movelangupdate();
    }

}
