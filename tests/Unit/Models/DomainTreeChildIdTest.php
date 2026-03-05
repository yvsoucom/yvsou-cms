<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainTreeChildId;

class DomainTreeChildIdTest extends TestCase
{
    protected $DomainTreeChildId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainTreeChildId = new DomainTreeChildId();
    }

}
