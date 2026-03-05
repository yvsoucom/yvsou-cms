<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainGrantGroup;

class DomainGrantGroupTest extends TestCase
{
    protected $DomainGrantGroup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainGrantGroup = new DomainGrantGroup();
    }

}
