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

    /** @test */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_by_the_current_user()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

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
        $user = $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => $this->faker->paragraph
        ]);

        $response = $this->getJson('/profiles/$user->name/notifications')->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_mark_a_notification_as_read()
    {
        $user = $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => $this->faker->paragraph
        ]);

        $this->assertCount(1, $user->unreadNotifications);

        $notification_id = $user->unreadNotifications->first()->id;

        $this->delete("profiles/$user->name/notifications/$notification_id");

        $this->assertCount(0, $user->refresh()->unreadNotifications);
    }
}
