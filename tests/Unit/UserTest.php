<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_be_an_owner_of_a_reply()
    {
        $user = User::factory()->hasReplies(1)->create();

        $this->assertInstanceOf(Reply::class, $user->replies->first());

    }
}
