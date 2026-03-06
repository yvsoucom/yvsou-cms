<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

final class LocaleServiceTest extends ServiceContractTestCase
{
    protected function serviceClass(): string
    {
        return \App\Services\LocaleService::class;
    }
}
