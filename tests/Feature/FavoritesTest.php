<?php

namespace Tests\Feature;

use App\Models\Favorite;
use App\Models\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling();

        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }


    /** @test */
    public function any_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }


    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->post('replies/' . $reply->id . '/favorites');
        $this->post('replies/' . $reply->id . '/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function unauthorized_users_can_not_unfavorites_replies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->delete("replies/$reply->id/favorites")
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete("replies/$reply->id/favorites")
            ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_unfavorite_a_reply()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $reply = create(Reply::class);

        $reply->favorite();

        $this->delete("replies/{$reply->id}/favorites")
            ->assertStatus(200);

        $this->assertCount(0, $reply->fresh()->favorites);

    }
}
