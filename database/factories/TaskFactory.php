<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(), // Generates a random task name
            'description' => $this->faker->paragraph(), // Generates a random task description
            'user_id' => User::factory(), // Associates the task with a user
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'), // Sets a random due date within the next month
            'priority' => $this->faker->randomElement(TaskPriority::class), // Randomly assigns a priority level
            'status' => $this->faker->randomElement(TaskStatus::class), // Randomly assigns a status
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
