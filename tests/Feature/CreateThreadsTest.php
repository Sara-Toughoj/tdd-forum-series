<?php

namespace Tests\Feature;

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
        $thread = create(Thread::class);

        $this->signIn();
        $this->post('threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->body)
            ->assertSee($thread->title);
    }
}
