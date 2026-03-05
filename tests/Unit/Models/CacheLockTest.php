<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\CacheLock;

class CacheLockTest extends TestCase
{
    protected $CacheLock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->CacheLock = new CacheLock();
    }

}
