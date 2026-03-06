<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

final class WebSocketSupervisorTest extends ServiceContractTestCase
{
    protected function serviceClass(): string
    {
        return \App\Services\WebSocketSupervisor::class;
    }
}
