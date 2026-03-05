<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainRightManager;

class DomainRightManagerTest extends TestCase
{
    protected $DomainRightManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainRightManager = new DomainRightManager();
    }

}
