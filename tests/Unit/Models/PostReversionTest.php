<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\PostReversion;

class PostReversionTest extends TestCase
{
    protected $PostReversion;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PostReversion = new PostReversion();
    }

    #[Test]
    public function test_post()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostReversion->post();
    }

    #[Test]
    public function test_user()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostReversion->user();
    }

    #[Test]
    public function test_modifiedBy()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostReversion->modifiedBy();
    }

}
