<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\UserLog;

class UserLogTest extends TestCase
{
    protected $UserLog;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UserLog = new UserLog();
    }

}
