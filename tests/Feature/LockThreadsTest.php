<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function non_admins_may_not_lock_a_thread()
    {
        $this->withExceptionHandling();
        $thread = create(Thread::class);
        $this->signIn($thread->creator);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse($thread->locked);

    }

    /** @test */
    public function admins_can_lock_a_thread()
    {
        $admin = User::factory()->admin()->create();

        $thread = create(Thread::class);

        $this->signIn($admin);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked, 'Failed asserting that the thread is locked');
    }

    /** @test */
    public function admins_can_unlock_a_thread()
    {
        $admin = User::factory()->admin()->create();

        $thread = create(Thread::class, ['locked' => true]);

        $this->signIn($admin);

        $this->delete(route('locked-threads.destroy', $thread))
            ->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked, 'Failed asserting that the thread is unlocked');
    }

    /** @test */
    public function once_locked_a_thread_may_not_receive_any_replies()
    {
        $this->withExceptionHandling();

        $this->signIn();
        $thread = create(Thread::class, ['locked' => true]);

        $this->postJson($thread->path() . '/replies', [
            'body' => $this->faker->sentence,
        ])->assertStatus(422);

    }
}
