<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Channel;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        app()->singleton(Recaptcha::class, function () {
            return \Mockery::mock(Recaptcha::class, function ($m) {
                $m->shouldReceive('passes')->andReturn(true);
            });
        });
    }

    /** @test */
    public function guests_may_not_see_create_thread_page()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('login');

        $this->post('/threads')
            ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_thread()
    {
        $response = $this->publishAThread([
            'title' => $title = $this->faker->sentence,
            'body' => $body = $this->faker->paragraph
        ]);

        $this->get($response->headers->get('Location'))
            ->assertSee($body)
            ->assertSee($title);
    }

    /** @test */
    public function users_should_verify_their_email_to_create_threads()
    {
        $thread = make(Thread::class);
        $user = create(User::class, [
            'email_verified_at' => null
        ]);

        $this->signIn($user);

        $this->post('threads', $thread->toArray())
            ->assertRedirect(route('verification.notice'));
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishAThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishAThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thead_requires_recaptcha_verifications()
    {
        unset(app()[Recaptcha::class]);
        $this->publishAThread(['g-recaptcha-response' => 'test'])
            ->assertSessionHasErrors('g-recaptcha-response');
    }

    /** @test */
    public function a_thread_requires_a_channel_id()
    {
        Channel::factory(2)->create();

        $this->publishAThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishAThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create(Thread::class, [
            'title' => $title = $this->faker->sentence,
        ]);

        $this->assertEquals($thread->fresh()->slug, $slug = Str::slug($title));

        $response = ($this->postJson(route('threads'),
            $thread->toArray() +
            ['g-recaptcha-response' => 'test']
        ))->json();

        $this->assertTrue(Thread::whereSlug("{$slug}-{$response['id']}")->exists());

    }

    /** @test */
    public function a_thread_with_a_title_that_ends_with_a_number_should_generate_the_proper_slug()
    {
        $this->signIn();

        $thread = create(Thread::class, [
            'title' => $title = $this->faker->sentence . '-' . $this->faker->randomNumber(2),
        ]);

        $response = ($this->postJson(route('threads'),
            $thread->toArray() +
            ['g-recaptcha-response' => 'test']
        ))->json();


        $slug = Str::slug($title);
        $this->assertTrue(Thread::whereSlug("{$slug}-{$response['id']}")->exists());

    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $thread = create(Thread::class);
        $this->withExceptionHandling();

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);

        $this->assertDatabaseHas((new Thread())->getTable(), ['id' => $thread->id]);

    }


    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();
        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->assertDatabaseHas((new Thread())->getTable(), ['id' => $thread->id]);
        $this->assertDatabaseHas((new Reply())->getTable(), ['id' => $reply->id]);

        $this->deleteJson($thread->path())
            ->assertStatus(204);

        $this->assertDatabaseMissing((new Thread())->getTable(), ['id' => $thread->id]);
        $this->assertDatabaseMissing((new Reply())->getTable(), ['id' => $reply->id]);


        $this->assertEquals(0, Activity::count());
    }


    protected function publishAThread($overrides)
    {
        $this->withExceptionHandling()->signIn();
        $thread = make(Thread::class, $overrides);
        return $this->post('threads',
            $thread->toArray() +
            ['g-recaptcha-response' => 'test']
        );
    }
}
