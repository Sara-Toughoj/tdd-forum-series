<?php

namespace Tests\Unit;

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
    public function a_user_can_has_threads()
    {
        $user = create(User::class);
        $thread = create(Thread::class, [
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(Thread::class, $user->threads->first());
        $this->assertInstanceOf(Collection::class, $user->threads);
    }
}
