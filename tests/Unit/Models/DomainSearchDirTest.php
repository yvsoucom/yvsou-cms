<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainSearchDir;

class DomainSearchDirTest extends TestCase
{
    protected $DomainSearchDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainSearchDir = new DomainSearchDir();
    }

}
