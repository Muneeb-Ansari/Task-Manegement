<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Task, User,TaskDueDateReminder};
use App\Notifications\TaskDueDateReminderNotification;

class SendDueDateReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $taskId;
    public string $reminderType;
    public string $dueSnapshot; // string for serialization safety

    public function __construct(int $taskId, string $reminderType, string $dueSnapshot)
    {
        $this->taskId = $taskId;
        $this->reminderType = $reminderType;
        $this->dueSnapshot = $dueSnapshot;
    }

    public function handle(): void
    {
        $task = Task::find($this->taskId);
        $user = User::find($task->assigned_to);

        if ($user) {
            // Send proper notification
            $user->notify(
                new TaskDueDateReminderNotification($task, $this->reminderType)
            );
        }

        // Mark reminder row as sent/completed
        TaskDueDateReminder::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->where('reminder_type', $this->reminderType)
            ->whereNull('reminder_sent_at')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->update([
                'reminder_sent_at' => now(),
                'is_completed'     => true,
            ]);
    }
}
