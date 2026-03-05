<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainUserSearchKey;

class DomainUserSearchKeyTest extends TestCase
{
    protected $DomainUserSearchKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainUserSearchKey = new DomainUserSearchKey();
    }

}
