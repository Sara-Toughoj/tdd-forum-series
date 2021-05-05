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
        $mentioned_user = create(User::class, ['name' => 'JaneDoe']);

        $this->signIn();
        $thread = create(Thread::class);

        $this->postJson($thread->path() . '/replies', [
            'body' => 'mentioning @' . $mentioned_user->name
        ])->assertStatus(201);

        $this->assertCount(1, $mentioned_user->notifications);


    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_character()
    {
        create(User::class, ['name' => 'JohnDoe1']);
        create(User::class, ['name' => 'JohnDoe2']);
        create(User::class, ['name' => 'JaneDoe']);

        $response = $this->getJson(route('user.index', ['name' => 'Jo']))->json();

        $this->assertCount(2, $response);

    }

}
