<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainAttachment;

class DomainAttachmentTest extends TestCase
{
    protected $DomainAttachment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainAttachment = new DomainAttachment();
    }

}
