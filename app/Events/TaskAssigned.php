<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\{User, Task};

class TaskAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Task $task,
        public User $assignee,
        public ?User $assignedBy = null,
        public string $actionType, 
        // public $oldDueDate = null,
        // public $newDueDate = null,
        // public $oldStatus = null,
        // public $newStatus = null,
    ) {
        //

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    //will work in future

    // public static function forDueDateUpdate(Task $task, User $assignee, $oldDueDate, $newDueDate)
    // {
    //     $event = new self($task, $assignee, null, 'due_date_updated');
    //     // $event->actionType = 'due_date_updated';
    //     $event->oldDueDate = $oldDueDate;
    //     $event->newDueDate = $newDueDate;
    //     return $event;
    // }

    // public static function forStatusChange(Task $task, User $assignee, $oldStatus, $newStatus)
    // {
    //     $event = new self($task, $assignee, null, 'status_changed');
    //     // $event->actionType = 'status_changed';
    //     $event->oldStatus = $oldStatus;
    //     $event->newStatus = $newStatus;
    //     return $event;
    // }
}
