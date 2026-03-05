<?php

namespace Tests\Unit\Http\Controllers\Search;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Controllers\Search\SearchController;

class SearchControllerTest extends TestCase
{
    protected $SearchController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->SearchController = new SearchController();
    }

    #[Test]
    public function test_search()
    {
        $this->markTestIncomplete('Auto generated');
        // $this->SearchController->search();
    }

}
