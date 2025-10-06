<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Task;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskDueDateReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    // public $task;
    // public $reminderType;

    public function __construct(public Task $task, public string $type)
    {
        //
        // $this->task = $task;
        // $this->reminderType = $reminderType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $due = optional($this->task->due_date)->format('d M Y, h:i A');
        return (new MailMessage)
            ->subject("⏰ Task Reminder: {$this->task->title}")
            ->greeting("Hi {$notifiable->name},")
            ->line("Aapke task **{$this->task->title}** ki due date **{$due}** hai.")
            ->line("Reminder: ".$this->getTimeLeftMessage())
            ->action('View Task', url("/tasks/{$this->task->id}"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title'   => $this->task->title,
            'type'    => $this->type,
            'due'     => $this->task->due_date?->toDateTimeString(),
            'message' => $this->getTimeLeftMessage(),
        ];
    }

    public function toDatabase($notifiable)
    {
        $timeLeft = $this->getTimeLeftMessage();

        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'due_date' => $this->task->due_date,
            'message' => "Task '{$this->task->title}' - {$timeLeft}",
            'url' => url("/tasks/{$this->task->id}"),
            'type' => 'due_date_reminder',
            'reminder_type' => $this->type,
        ];
    }

    public function toBroadcast($notifiable)
    {
        $timeLeft = $this->getTimeLeftMessage();

        return new BroadcastMessage([
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'message' => "Task '{$this->task->title}' - {$timeLeft}",
            'url' => url("/tasks/{$this->task->id}"),
        ]);
    }

    private function getTimeLeftMessage()
    {
        $now = now();
        $dueDate = $this->task->due_date;

        if ($dueDate->diffInDays($now) > 0) {
            return $dueDate->diffInDays($now) . ' days remaining';
        } elseif ($dueDate->diffInHours($now) > 0) {
            return $dueDate->diffInHours($now) . ' hours remaining';
        } else {
            return $dueDate->diffInMinutes($now) . ' minutes remaining';
        }
    }
}
