<?php

namespace Tests\Unit\Livewire;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Livewire\PostReversionDiff;

class PostReversionDiffTest extends TestCase
{
    protected $PostReversionDiff;

    protected function setUp(): void
    {
        parent::setUp();
        $this->PostReversionDiff = new PostReversionDiff();
    }

    #[Test]
    public function test_mount()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostReversionDiff->mount();
    }

    #[Test]
    public function test_render()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->PostReversionDiff->render();
    }

}
