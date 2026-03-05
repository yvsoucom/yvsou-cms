<?php

namespace Tests\Unit\Console\Commands;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Console\Commands\CleanupTmpFiles;

class CleanupTmpFilesTest extends TestCase
{
    protected $CleanupTmpFiles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CleanupTmpFiles = new CleanupTmpFiles();
    }

    #[Test]
    public function test_handle()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->CleanupTmpFiles->handle();
    }

}
