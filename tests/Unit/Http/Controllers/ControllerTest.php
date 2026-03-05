<?php

namespace Tests\Unit\Http\Controllers;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Controller;

class ControllerTest extends TestCase
{
    protected $Controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->Controller = new Controller();
    }

}
