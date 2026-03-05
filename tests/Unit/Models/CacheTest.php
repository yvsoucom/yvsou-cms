<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Cache;

class CacheTest extends TestCase
{
    protected $Cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Cache = new Cache();
    }

}
