<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Task,TaskDueDateReminder};
use Illuminate\Support\Facades\Log;

class DispatchDueDateReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $task;

    public function __construct(Task $task)
    {
        //
        $this->task = $task;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dd('raeaching');
        //
        \Log::error('Task not found in DispatchDueDateReminders');

        $this->task->dueDateReminders()
            ->where('is_completed', false)
            ->update(['is_completed' => true]);

        // Set reminder for new due date
        $this->scheduleReminders();
    }

    private function scheduleReminders()
    {
        $dueDate = $this->task->due_date;
        $userId = $this->task->assigned_to;

        $reminderIntervals = [
            '1_day_before' => $dueDate->copy()->subDay(),
            '6_hours_before' => $dueDate->copy()->subHours(6),
            '3_hours_before' => $dueDate->copy()->subHours(3),
            '1_hour_before' => $dueDate->copy()->subHour(),
            '30_minutes_before' => $dueDate->copy()->subMinutes(30),
        ];

        foreach ($reminderIntervals as $type => $reminderTime) {
            // Only schedule if reminder time is in future
            if ($reminderTime->isFuture()) {
                TaskDueDateReminder::create([
                    'task_id' => $this->task->id,
                    'user_id' => $userId,
                    'due_date' => $dueDate,
                ]);

                SendDueDateReminder::dispatch($this->task, $type)
                    ->delay($reminderTime);
            }
        }
    }
}
