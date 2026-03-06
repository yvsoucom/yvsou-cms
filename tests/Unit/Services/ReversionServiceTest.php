<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

final class ReversionServiceTest extends ServiceContractTestCase
{
    protected function serviceClass(): string
    {
        return \App\Services\ReversionService::class;
    }
}
