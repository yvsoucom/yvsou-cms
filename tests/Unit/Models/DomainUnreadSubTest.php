<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainUnreadSub;

class DomainUnreadSubTest extends TestCase
{
    protected $DomainUnreadSub;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainUnreadSub = new DomainUnreadSub();
    }

}
