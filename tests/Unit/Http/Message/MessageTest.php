<?php

namespace Tests\Unit\Http\Message;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Message\Message;

class MessageTest extends TestCase
{
    protected $Message;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Message = new Message();
    }

}
