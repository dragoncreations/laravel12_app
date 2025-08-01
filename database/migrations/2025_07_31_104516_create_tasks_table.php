<?php

use App\Enums\TaskPriority;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Added 'name' column for task name
            $table->text('description')->nullable(); // Added 'description' column for task details
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to link tasks to users
            $table->timestamp('due_date'); // Added 'due_date' column for task deadline
            $table->string('priority')->default(TaskPriority::MEDIUM); // Added 'priority' column to categorize task urgency
            $table->string('status'); // Added 'status' column to track task progress
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
