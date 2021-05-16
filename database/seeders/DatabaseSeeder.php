<?php

namespace Database\Seeders;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Thread::factory(30)
            ->hasReplies(5)
            ->create();

        User::factory()->create([
            'email' => 'user@mail.com',
            'password' => bcrypt('secret')
        ]);
    }
}
