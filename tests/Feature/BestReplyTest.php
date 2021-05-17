<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function a_thread_creator_may_mark_any_reply_as_the_best_reply()
    {
        $thread = Thread::factory()->hasReplies(2)->create();

        $this->signIn($thread->creator);

        $reply = $thread->replies[1];

        $this->assertFalse($reply->isBest());

        $this->postJson(route('best-replies.store', [
            'reply' => $reply
        ]));

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    public function only_the_thread_creator_may_mark_the_reply_as_best()
    {
        $this->withExceptionHandling();
        $thread = Thread::factory()->hasReplies()->create();
        $this->signIn();

        $this->postJson(route('best-replies.store', [
            'reply' => $reply = $thread->replies->first()
        ]))->assertForbidden();

        $this->assertFalse($reply->isBest());
    }

    /** @test */
    public function if_a_best_reply_is_deleted_then_the_thread_is_properly_updated_to_reflect_that()
    {
        $reply = create(Reply::class);
        $this->signIn($reply->owner);

        $reply->thread->setBestReply($reply);

        $this->deleteJson(route('replies.destroy', ['reply' => $reply]));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}
