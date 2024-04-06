<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'assignee_id', 'due_date', 'parent_id','status_id'];


    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'dependency_id');
    }

    public function dependents()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'dependency_id', 'task_id');
    }


    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
