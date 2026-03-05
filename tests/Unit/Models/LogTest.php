<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Log;

class LogTest extends TestCase
{
    protected $Log;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Log = new Log();
    }

}
