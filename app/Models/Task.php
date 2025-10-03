<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\jobs\DispatchDueDateReminders;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to',
        'created_by'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    // creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // assignee
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // scope to get tasks relevant to a user
    public function scopeForUser($query, $userId)
    {
        return $query->where('created_by', $userId)
            ->orWhere('assigned_to', $userId);
    }
    // Due date reminders relationship
    public function dueDateReminders()
    {
        return $this->hasMany(TaskDueDateReminder::class, 'task_id');
    }

    // Detect the change in due_date
    public static function boot()
    {
        parent::boot();

        static::updating(function ($task) {
            if ($task->isDirty('due_date')) {
                //Automatically dispatch the job when due date changes
                DispatchDueDateReminders::dispatch($task);
            }
        });
    }
}
