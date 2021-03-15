<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInFormTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $thread = create(Thread::class);
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $this->post("{$thread->path()}/replies")
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn($user = create(User::class));
        $thread = create(Thread::class);

        $this->post($thread->path() . '/replies', [
            'body' => $body = $this->faker->paragraph,
        ]);

        $new_reply = $thread->replies->first();

        $this->assertEquals($new_reply->body, $body);
        $this->assertEquals($new_reply->owner->id, $user->id);

        $this->get($thread->path())->assertSee($new_reply->body);
    }
}
