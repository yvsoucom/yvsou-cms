<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\JobBatch;

class JobBatchTest extends TestCase
{
    protected $JobBatch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->JobBatch = new JobBatch();
    }

}
