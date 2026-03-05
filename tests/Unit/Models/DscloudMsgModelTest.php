<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DscloudMsgModel;

class DscloudMsgModelTest extends TestCase
{
    protected $DscloudMsgModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DscloudMsgModel = new DscloudMsgModel();
    }

}
