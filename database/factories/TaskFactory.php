<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'status' => 'pending',
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'assigned_to' => $this->faker->optional()->randomElement($users),
            'created_by' => $this->faker->randomElement($users),
        ];
    }
}
