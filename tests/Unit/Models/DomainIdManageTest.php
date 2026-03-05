<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainIdManage;

class DomainIdManageTest extends TestCase
{
    protected $DomainIdManage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainIdManage = new DomainIdManage();
    }

}
