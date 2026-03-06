<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

final class CommentServiceTest extends ServiceContractTestCase
{
    protected function serviceClass(): string
    {
        return \App\Services\CommentService::class;
    }
}
