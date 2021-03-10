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
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $this->post("threads/1/replies");
    }

    /** @test */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->create();

        $this->actingAs($user)->post("threads/$thread->id/replies", [
            'body' => $body = $this->faker->paragraph,
        ]);

        $new_reply = $thread->replies->first();

        $this->assertCount(1, $thread->replies);
        $this->assertEquals($new_reply->body, $body);
        $this->assertEquals($new_reply->owner->id, $user->id);

        $this->get($thread->path())->assertSee($new_reply->body);
    }
}
