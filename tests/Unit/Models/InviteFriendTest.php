<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\InviteFriend;

class InviteFriendTest extends TestCase
{
    protected $InviteFriend;

    protected function setUp(): void
    {
        parent::setUp();
        $this->InviteFriend = new InviteFriend();
    }

}
