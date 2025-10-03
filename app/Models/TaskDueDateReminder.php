<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDueDateReminder extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'user_id', 'due_date', 'reminder_sent_at', 'is_completed'];

    protected $casts = [
        'due_date' => 'datetime',
        'reminder_sent_at' => 'datetime',
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
