<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\GroupEmail;

class GroupEmailTest extends TestCase
{
    protected $GroupEmail;

    protected function setUp(): void
    {
        parent::setUp();
        $this->GroupEmail = new GroupEmail();
    }

}
