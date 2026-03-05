<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainMsgCenter;

class DomainMsgCenterTest extends TestCase
{
    protected $DomainMsgCenter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainMsgCenter = new DomainMsgCenter();
    }

}
