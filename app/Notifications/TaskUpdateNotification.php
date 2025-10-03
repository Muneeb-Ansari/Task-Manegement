<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\{Task,User};

class TaskUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Task $task,
        public ?User $assignedBy = null
    )
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
         return (new MailMessage)
            ->subject('Task Updated')
            ->greeting('Hello!')
            ->line('Task has been updated.')
            ->line('Task: '.($this->task->title ?? 'Task'))
            ->action('View Task', route('tasks.show', $this->task->id))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'task_id'     => $this->task->id,
            'title'       => $this->task->title,
            'assigned_by' => $this->assignedBy?->only(['id','name','email']),
            'message'     => sprintf('Task "%s" has been updated.', $this->task->title ?? ('Task #'.$this->task->id)),
            'url'         => route('tasks.show', $this->task->id),
        ];
    }

     public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
