<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(50),
            'content' => fake()->text(),
            'status' => Note::STATUS_PUBLISHED,
            'user_id' => User::factory(),
        ];
    }

    public function forUser(User $user): self
    {
        return $this->state(fn () => ['user_id' => $user->id]);
    }
}
