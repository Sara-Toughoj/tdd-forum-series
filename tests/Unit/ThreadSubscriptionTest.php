<?php

namespace Tests\Unit;

use App\Models\Thread;
use App\Models\ThreadSubscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $subscription = ThreadSubscription::factory()->hasUser()->create();
        $this->assertInstanceOf(User::class, $subscription->user);
    }

    /** @test */
    public function it_belongs_to_a_thread()
    {
        $subscription = ThreadSubscription::factory()->hasThread()->create();
        $this->assertInstanceOf(Thread::class, $subscription->thread);
    }
}
