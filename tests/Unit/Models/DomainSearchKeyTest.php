<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainSearchKey;

class DomainSearchKeyTest extends TestCase
{
    protected $DomainSearchKey;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainSearchKey = new DomainSearchKey();
    }

}
