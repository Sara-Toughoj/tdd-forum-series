<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();
        $thread = create(Thread::class);

        $this->assertDatabaseHas((new Activity())->getTable(), [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => Thread::class
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();
        $reply = create(Reply::class);

        $this->assertDatabaseHas((new Activity())->getTable(), [
            'type' => 'created_reply',
            'user_id' => auth()->id(),
            'subject_id' => $reply->id,
            'subject_type' => Reply::class
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $reply->id);
    }

    /** @test */
    public function it_fetches_feed_for_user()
    {
        $this->signIn();
        create(Thread::class, ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update([
            'created_at' => now()->subWeek()
        ]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            now()->subWeek()->format('Y-m-d')
        ));


    }
}
