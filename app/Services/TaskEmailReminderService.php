<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailNotificationJob;

class TaskEmailReminderService
{
    public function send(Task $task): void
    {
        $diff = (Carbon::now())->diffInDays(Carbon::parse($task->due_date));
        $job = DB::table("jobs")->where("task_id", $task->id);

        if ($job->first()) {
            if ($diff > 1) {
                $job->update(['available_at' => now()->addDays($diff - 1)->timestamp]);
            } else {
                $job->update(['available_at' => $job->first()->created_at]);
            }
        } else {
            $job = (new SendEmailNotificationJob($task))->onQueue('tasks');

            if ($diff > 1) {
                $job->delay(now()->addDays($diff - 1));
            }

            dispatch($job);

            $regex = 'Task.*?id\\\\";i:' . $task->id . ';';

            DB::table('jobs')->where('payload', 'REGEXP', $regex)->update(['task_id' => $task->id]);
        }
    }
}
