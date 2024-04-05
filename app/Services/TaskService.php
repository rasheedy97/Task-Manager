<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskService
{
    public function getTasks()
    {
    }

    public function createTask(TaskRequest $request)
    {
        Task::create($request->validated());
        $message = $request->exists('parent_id') ? "Sub-task created successfully" : "Task created successfully";
        return $message;
    }
}
