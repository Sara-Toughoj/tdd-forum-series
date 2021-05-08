<?php

namespace Tests\Unit;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function a_user_can_be_an_owner_of_a_reply()
    {
        $user = User::factory()->hasReplies(1)->create();

        $this->assertInstanceOf(Reply::class, $user->replies->first());

    }

    /** @test */
    public function a_user_can_have_threads()
    {
        $user = create(User::class);
        create(Thread::class, [
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(Thread::class, $user->threads->first());
        $this->assertInstanceOf(Collection::class, $user->threads);
    }


    /** @test */
    public function a_user_has_activity()
    {
        $user = User::factory()->hasActivity()->create();

        $this->assertInstanceOf(Activity::class, $user->activity->first());
        $this->assertInstanceOf(Collection::class, $user->activity);
    }

    /** @test */

    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create(User::class);

        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($user->lastReply->id, $reply->id);

    }

    /** @test */
    public function a_user_can_determine_their_avatar_path()
    {
        $user = create(User::class);
        $this->assertEquals(asset('storage/avatars/default.png'), $user->avatar);
        $user->avatar_path = 'avatars/me.jpg';
        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar);
    }


}
