<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainGrantUser;

class DomainGrantUserTest extends TestCase
{
    protected $DomainGrantUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainGrantUser = new DomainGrantUser();
    }

}
