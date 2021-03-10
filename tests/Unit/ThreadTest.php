<?php

namespace Tests\Unit;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_thread_has_replies()
    {
        $thread = Thread::factory()->hasReplies(1)->create();

        $this->assertInstanceOf(Reply::class, $thread->replies->first());
    }

    /** @test */
    public function a_thread_has_an_creator()
    {
        $thread = Thread::factory()->hasCreator(1)->create();

        $this->assertInstanceOf(User::class, $thread->creator);
    }

    /** @test */
    public function a_thread_can_add_reply()
    {
        $thread = Thread::factory()->create();
        $user = User::factory()->create();

        $this->assertCount(0, $thread->replies);

        $thread->addReply([
            'body' => $body = $this->faker->paragraph,
            'user_id' => $user->id,
        ]);

        $thread->refresh();

        $this->assertCount(1, $thread->replies);

    }


}
