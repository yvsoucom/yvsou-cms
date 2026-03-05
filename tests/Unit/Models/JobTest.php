<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\Job;

class JobTest extends TestCase
{
    protected $Job;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Job = new Job();
    }

}
