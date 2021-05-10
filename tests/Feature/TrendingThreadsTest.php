<?php

namespace Tests\Feature;

use App\Models\Thread;
use App\Trending;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrendingThreadsTest extends TestCase
{
    protected $trending;

    protected function setUp(): void
    {
        parent::setUp();
        $this->trending = new Trending();
        $this->trending->reset();
    }

    use RefreshDatabase;

    /** @test */
    public function it_increments_a_threads_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());
        $thread = create(Thread::class);
        $this->call('get', $thread->path());
        $trending = $this->trending->get();
        $this->assertEquals(1, count($trending));
        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
