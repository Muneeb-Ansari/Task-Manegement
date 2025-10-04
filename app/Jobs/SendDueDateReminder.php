<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Task, User};
use App\Notifications\TaskDueDateReminderNotification;

class SendDueDateReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;
    public $reminderType;

    public function __construct(Task $task, $reminderType)
    {
        $this->task = $task;
        $this->reminderType = $reminderType;
    }

    public function handle(): void
    {
        if ($this->task->status !== 'completed' && $this->task->due_date->isFuture()) {
            $user = User::find($this->task->assigned_to);

            if ($user) {
                // Send proper notification
                $user->notify(
                    new TaskDueDateReminderNotification($this->task, $this->reminderType)
                );

                // Mark reminder as sent
                $this->task->dueDateReminders()
                    ->where('is_completed', false)
                    ->whereNull('reminder_sent_at')
                    ->update(['reminder_sent_at' => now()]);
            }
        }
    }
}
