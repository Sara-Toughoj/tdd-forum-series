<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->word,
            'user_id' => User::factory(),
            'subject_id' => $this->faker->randomNumber(4),
            'subject_type' => $this->faker->word,
        ];
    }

    public function thread()
    {
        return $this->state(function (array $attributes) {
            return [
                'subject_id' => Thread::factory(),
                'subject_type' => Thread::class,
            ];
        });
    }

    public function reply()
    {
        return $this->state(function (array $attributes) {
            return [
                'subject_id' => Reply::factory(),
                'subject_type' => Reply::class,
            ];
        });
    }
}
