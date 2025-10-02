<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TaskAssignedNotification;

class SendTaskAssignedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskAssigned $event): void
    {
        //
        // $event->assignee->notify(
        //     new TaskAssignedNotification($event->task, $event->assignedBy, $event->actionType, $event->oldDueDate, $event->newDueDate)
        // );
        switch ($event->actionType) {
            case 'created':
            case 'updated_by_admin':
                $this->sendTaskAssignedNotification($event);
                break;
            // case 'updated_by_admin':
            //     $this->sendUpdateNotificationToUser($event);
            //     break;
            case 'updated_by_user':
                $this->sendUpdateNotificationToAdmin($event);
                break;

            default:
                # code...
                break;
        }
    }
    private function sendTaskAssignedNotification($event)
    {
        $event->assignee->notify(
            new TaskAssignedNotification($event->task, $event->assignedBy)
        );
    }

    // private function sendUpdateNotificationToUser($event)
    // {
    //      $event->assignee->notify(
    //         new TaskAssignedNotification($event->task, $event->assignedBy)
    //     );
    // }

    private function sendUpdateNotificationToAdmin($event)
    {
         $event->assignedBy->notify(
            new TaskAssignedNotification($event->task, $event->assignee)
        );
    }
}
