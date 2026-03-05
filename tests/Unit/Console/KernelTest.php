<?php

namespace Tests\Unit\Console;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Console\Kernel;

class KernelTest extends TestCase
{
    protected $Kernel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Kernel = new Kernel();
    }

}
