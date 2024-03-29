<?php

namespace Tests\Unit;

use App\Models\Favorite;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
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

    /** @test */
    public function a_reply_knows_it_was_just_published()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }

    /** @test */
    public function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new Reply([
            'body' => '@JaneDoe and @JohnDoe'
        ]);
        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /** @test */
    public function it_wraps_mentioned_username_in_the_body_with_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @Jane-Doe.'
        ]);
        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>.',
            $reply->body
        );
    }

    /** @test */
    public function it_knows_if_it_is_the_best_reply()
    {
        $reply = create(Reply::class);
        $this->assertFalse($reply->isBest());

        $reply->thread->update([
            'best_reply_id' => $reply->id
        ]);

        $this->assertTrue($reply->fresh()->isBest());
    }

    /** @test */
    public function a_reply_body_is_sanitized_automatically()
    {
        $reply = make(Reply::class, ['body' => '<script>alert("bad")</script><p>This is Okay</p>']);
        $this->assertEquals('<p>This is Okay</p>', $reply->body);
    }
}
