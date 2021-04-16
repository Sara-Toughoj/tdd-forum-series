<?php

namespace Tests\Unit;

use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
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
    public function a_thread_can_make_a_thread_path()
    {
        $thread = create(Thread::class);
        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    /** @test */
    public function a_thread_can_add_reply()
    {
        $thread = create(Thread::class);

        $this->assertCount(0, $thread->replies);

        $thread->addReply([
            'body' => $body = $this->faker->paragraph,
            'user_id' => create(User::class)->id,
        ]);

        $this->assertCount(1, $thread->refresh()->replies);
    }

    /** @test */
    public function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $thread = create(Thread::class);
        $this->signIn();

        $thread->subscribe()->addReply([
            'body' => $body = $this->faker->paragraph,
            'user_id' => create(User::class)->id,
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = Thread::factory()->hasChannel()->create();
        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create(Thread::Class);

        $thread->subscribe($user_id = 1);

        $subscriptions = $thread->subscriptions()->where('user_id', $user_id)->get();

        $this->assertCount(1, $subscriptions);
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create(Thread::class);

        $thread->subscribe($user_id = 1);

        $thread->unsubscribe($user_id);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    public function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $this->signIn();
        $thread = create(Thread::class);

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
}
