<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DscloudMsgType;

class DscloudMsgTypeTest extends TestCase
{
    protected $DscloudMsgType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DscloudMsgType = new DscloudMsgType();
    }

}
