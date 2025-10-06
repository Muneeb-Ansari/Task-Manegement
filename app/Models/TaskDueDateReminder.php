<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDueDateReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id','user_id','reminder_type','scheduled_for','reminder_sent_at','due_snapshot','is_completed'
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'due_snapshot' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
