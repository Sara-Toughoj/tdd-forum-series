<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function mentioned_users_in_replies_are_notified()
    {
        $mentioned_user = create(User::class);

        $this->signIn();
        $thread = create(Thread::class);

        $this->postJson($thread->path() . '/replies', [
            'body' => 'mentioning @' . $mentioned_user->name
        ])->assertStatus(201);

        $this->assertCount(1, $mentioned_user->notifications);


    }

}
