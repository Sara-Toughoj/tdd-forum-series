<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInThreadsTest extends TestCase
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

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function unauthorized_users_can_not_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create(Reply::class);

        $this->delete("/replies/$reply->id")
            ->assertRedirect('login');

        $this->signIn();

        $this->delete("/replies/$reply->id")
            ->assertStatus(403);

    }

    /** @test */
    public function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create(Reply::class, [
            'user_id' => auth()->id()
        ]);

        $this->delete("/replies/$reply->id");

        $this->assertDatabaseMissing((new Reply())->getTable(), ['id' => $reply->id]);

    }
}