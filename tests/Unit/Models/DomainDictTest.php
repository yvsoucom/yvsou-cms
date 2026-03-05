<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\DomainDict;

class DomainDictTest extends TestCase
{
    protected $DomainDict;

    protected function setUp(): void
    {
        parent::setUp();
        $this->DomainDict = new DomainDict();
    }

    #[Test]
    public function test_entries()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->DomainDict->entries();
    }

}
