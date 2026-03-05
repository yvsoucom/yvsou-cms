<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainDownloadZip;

class DomainDownloadZipTest extends TestCase
{
    protected $DomainDownloadZip;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainDownloadZip = new DomainDownloadZip();
    }

}
