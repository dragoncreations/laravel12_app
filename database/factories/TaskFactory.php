<?php

namespace Database\Factories;

use App\Models\Task;
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
            'priority' => $this->faker->randomElement([Task::PRIORITY_LOW, Task::PRIORITY_MEDIUM, Task::PRIORITY_HIGH]), // Randomly assigns a priority level
            'status' => $this->faker->randomElement([Task::STATUS_TO_DO, Task::STATUS_IN_PROGRESS, Task::STATUS_DONE]), // Randomly assigns a status
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
