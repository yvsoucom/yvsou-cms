<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainMsgCast;

class DomainMsgCastTest extends TestCase
{
    protected $DomainMsgCast;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainMsgCast = new DomainMsgCast();
    }

}
