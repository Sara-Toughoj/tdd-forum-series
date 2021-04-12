<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscribeToThreadTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test */
    public function unauthorized_users_can_subscribe_to_threads()
    {
        $this->withExceptionHandling();
        $thread = create(Thread::class);

        $this->post("{$thread->path()}/subscriptions")
            ->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $this->post("{$thread->path()}/subscriptions")
            ->assertStatus(200);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => $this->faker->paragraph
        ]);
    }

    /** @test */
    public function unauthorized_users_can_unsubscribe_from_threads()
    {
        $this->withExceptionHandling();
        $thread = create(Thread::class);

        $this->post("{$thread->path()}/subscriptions")
            ->assertRedirect('login');
    }


    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $thread->subscribe(auth()->id());

        $this->delete("{$thread->path()}/subscriptions")
            ->assertStatus(200);

        $this->assertCount(0, $thread->subscriptions);
    }
}
