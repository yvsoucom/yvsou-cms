<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Services\ConstantService;

class ConstantServiceTest extends TestCase
{
    protected $ConstantService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ConstantService = new ConstantService();
    }

}
