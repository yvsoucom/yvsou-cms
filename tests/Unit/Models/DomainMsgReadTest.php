<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainMsgRead;

class DomainMsgReadTest extends TestCase
{
    protected $DomainMsgRead;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainMsgRead = new DomainMsgRead();
    }

}
