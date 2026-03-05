<?php

namespace Tests\Unit\Http\Controllers\Post;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Post\FileLibraryController;

class FileLibraryControllerTest extends TestCase
{
    protected $FileLibraryController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->FileLibraryController = new FileLibraryController();
    }

    #[Test]
    public function test_index()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->FileLibraryController->index();
    }

}
