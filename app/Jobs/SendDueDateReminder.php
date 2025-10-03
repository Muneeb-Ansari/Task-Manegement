<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Task,TaskDueDateReminder};

class SendDueDateReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $task;
    public $reminderType;

    public function __construct(Task $task, $reminderType)
    {
        //
        $this->task = $task;
        $this->reminderType = $reminderType;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        // Check if task is still pending and due date hasn't changed
        if (
            $this->task->status !== 'completed' &&
            $this->task->due_date->isFuture()
        ) {
             $taskArray = [
                'id' => $this->task->id,
                'title' => $this->task->title,
                'due_date' => $this->task->due_date,
                'user_id' => $this->task->assigned_to,
                'status' => $this->task->status,
            ];

            //Send notification to user
            $this->task->user->notify(
                new TaskDueDateReminder($taskArray, $this->reminderType)
            );

            // mark the reminder
            $this->task->dueDateReminders()
                ->where('is_completed', false)
                ->where('reminder_sent_at', null)
                ->update(['reminder_sent_at' => now()]);
        }
    }
}
