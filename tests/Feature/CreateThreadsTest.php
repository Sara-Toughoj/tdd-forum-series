<?php

namespace Tests\Feature;

use App\Models\Channel;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;


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
    public function an_authenticated_user_can_create_a_thread()
    {
        $thread = make(Thread::class);

        $this->signIn();
        $response = $this->post('threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->body)
            ->assertSee($thread->title);
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
    public function a_thread_requires_a_channel_id()
    {
        Channel::factory(2)->create();

        $this->publishAThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishAThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    protected function publishAThread($overrides)
    {
        $this->withExceptionHandling()->signIn();
        $thread = make(Thread::class, $overrides);
        return $this->post('threads', $thread->toArray());
    }
}
