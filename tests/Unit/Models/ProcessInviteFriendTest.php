<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\ProcessInviteFriend;

class ProcessInviteFriendTest extends TestCase
{
    protected $ProcessInviteFriend;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ProcessInviteFriend = new ProcessInviteFriend();
    }

}
