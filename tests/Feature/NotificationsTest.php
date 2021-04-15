<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user = null;


    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->signIn();
    }

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->assertCount(0, auth()->user()->notifications);
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => $this->faker->paragraph
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => $this->faker->paragraph
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $this->createNotification();

        $this->assertCount(1,
            $this->getJson('/profiles/$user->name/notifications')->json()
        );
    }

    /** @test */
    public function a_user_mark_a_notification_as_read()
    {

        $this->createNotification();

        $this->assertCount(1, $this->user->unreadNotifications);

        $notification_id = $this->user->unreadNotifications->first()->id;

        $this->delete("profiles/$this->user->name/notifications/$notification_id");

        $this->assertCount(0, $this->user->refresh()->unreadNotifications);
    }

    protected function createNotification($user_id = null)
    {
        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => $user_id ?? create(User::class)->id,
            'body' => $this->faker->paragraph
        ]);

    }
}
