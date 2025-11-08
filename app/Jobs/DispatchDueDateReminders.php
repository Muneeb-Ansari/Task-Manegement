<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Task, TaskDueDateReminder};
use Illuminate\Support\Carbon;
use App\Helpers\ErrorHandler;

class DispatchDueDateReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $taskId;

    public function __construct($taskId)
    {
        //
        $this->taskId = $taskId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $task = Task::with('assignee')->find($this->taskId);

        //for testing purpose

        if (! $task || ! $task->assignee || ! $task->due_date) return;

        $task->dueDateReminders()->where('is_completed', false)->update(['is_completed' => true]);

        $this->scheduleReminders($task);
    }

    private function scheduleReminders($task)
    {

        try {
            //code...
            $due = Carbon::parse($task->due_date);

            $plan = [
                '24h'  => $due->copy()->subDay(),
                '6h'   => $due->copy()->subHours(6),
                '1h'   => $due->copy()->subHour(),
                '30m'  => $due->copy()->subMinutes(30),
                'due'  => $due->copy(),
            ];

            // for testing
            // $due = Carbon::now()->addMinutes(3);
            // $plan = [
            //     '2m'  => $due->copy()->subMinute(2),
            //     '1m' => $due->copy()->subMinute(),
            //     '30s' => $due->copy()->subSeconds(30),
            //     'due' => $due->copy(),
            // ];

            foreach ($plan as $type => $when) {
                // if ($when->isFuture()) {
                    // DB record so we can mark sent later
                    TaskDueDateReminder::create([
                        'task_id'       => $task->id,
                        'user_id'       => $task->assigned_to,
                        'reminder_type' => $type,
                        'scheduled_for' => $when,
                        'due_snapshot'  => $due, // guard against due changed later
                        'due_date'     => $due,
                    ]);

                    SendDueDateReminder::dispatch($task->id, $type, $due->toDateTimeString())
                        ->delay($when);
                // }
            }
        } catch (\Exception $th) {
            ErrorHandler::fail($th, 'Unable to send the notification due to getting error.');
        }
    }
}
