<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guests_may_not_add_threads()
    {
        $this->withoutExceptionHandling();
        $this->expectException(AuthenticationException::class);
        $thread = Thread::factory()->make();
        $this->post('threads', $thread->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_create_a_thread()
    {
        $user = User::factory()->create();
        $thread = Thread::factory()->make([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)->post('threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->body)
            ->assertSee($thread->title);

    }
}
