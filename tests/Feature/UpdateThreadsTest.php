<?php

namespace Tests\Feature;

use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase, WithFaker;


    /** @test */
    public function a_thread_can_be_updated_by_its_creator()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => $title = $this->faker->sentence,
            'body' => $body = $this->faker->paragraph
        ])->assertStatus(200);

        $thread = $thread->fresh();
        $this->assertEquals($thread->title, $title);
        $this->assertEquals($thread->body, $body);
    }

    /** @test */
    public function a_thread_requires_a_title_and_body_to_be_updated()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => $this->faker->sentence,
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => $this->faker->paragraph,
        ])->assertSessionHasErrors('title');

    }

    /** @test */
    public function unauthorized_users_may_not_update_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);
        $this->signIn();

        $this->patch($thread->path(), [
            'title' => $title = $this->faker->sentence,
            'body' => $body = $this->faker->paragraph
        ])->assertStatus(403);

        $thread = $thread->fresh();
        $this->assertNotEquals($thread->title, $title);
        $this->assertNotEquals($thread->body, $body);

    }
}
