<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainUploadAttach;

class DomainUploadAttachTest extends TestCase
{
    protected $DomainUploadAttach;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainUploadAttach = new DomainUploadAttach();
    }

}
