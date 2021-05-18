<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function an_administrator_can_lock_any_thread()
    {
        $this->withExceptionHandling();

        $this->signIn();
        $thread = create(Thread::class);
        $thread->lock();

        $this->postJson($thread->path() . '/replies', [
            'body' => $this->faker->sentence,
        ])->assertStatus(422);

    }
}
