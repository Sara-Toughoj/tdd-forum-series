<?php

namespace Tests\Unit;

use App\Models\Favorite;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = create(Reply::class);

        $this->assertInstanceOf(User::class, $reply->owner);
    }


    /** @test */
    public function it_has_favorites()
    {
        $reply = Reply::factory()->hasFavorites(1)->create();

        $this->assertInstanceOf(Collection::class, $reply->favorites);
        $this->assertInstanceOf(Favorite::class, $reply->favorites->first());
    }

    /** @test */
    public function a_reply_can_be_favorited()
    {
        $this->signIn();
        $reply = create(Reply::class);
        $reply->favorite();

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function a_reply_can_have_a_thread()
    {
        $reply = Reply::factory()->hasThread()->create();

        $this->assertInstanceOf(Thread::class, $reply->thread);
    }
}
