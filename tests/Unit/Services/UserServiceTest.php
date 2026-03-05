<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\UserService;

class UserServiceTest extends TestCase
{
    protected $UserService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->UserService = new UserService();
    }

}
