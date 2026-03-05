<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainMsgTemplate;

class DomainMsgTemplateTest extends TestCase
{
    protected $DomainMsgTemplate;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainMsgTemplate = new DomainMsgTemplate();
    }

}
