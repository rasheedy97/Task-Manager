<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function getAllTasks($request)
    {

        $user = Auth::user();
        $query = Task::query();

        if ($request->filled(['due_start_day', 'due_end_day'])) {

            $dueDateStart = Carbon::parse($request->input('due_start_day'))->startOfDay();
            $dueDateEnd = Carbon::parse($request->input('due_end_day'))->endOfDay();
            $query->whereBetween('due_date', [$dueDateStart, $dueDateEnd]);
        }

        $query->when($request->filled('status'), function ($query) use ($request) {

            $query->where('status_id', $request->input('status'));
        });

        $query->when($request->filled('assignee_id') && $user->hasRole('Manager'), function ($query) use ($request) {
            $query->where('assignee_id', $request->input('assignee_id'));
        });

        $query->when($user->hasRole('User'), function ($query) use ($user) {

            $query->where('assignee_id', $user->id);
        });

        return $query->get();
    }


    public function getTask($task)
    {
        $user = Auth::user();

        if ($user->hasRole('User')) {
            if ($task->assignee_id !== $user->id) {
                throw new \Exception('Unauthorized to update task status');

            }
        }

        $task->load('dependencies');
        return $task;
    }

    public function createTask(TaskRequest $request)
    {
        $task = Task::create($request->validated());
        if ($request->exists('dependant_id')) {
            $task->dependencies()->attach($request->dependant_id);
        }
        return 'Task created successfully';
    }

    public function updateTask($request,$task){
        $user = Auth::user();
        $validatedData = $request->validated();
        if ($user->hasRole('User') && $task->assignee_id !== $user->id) {
            throw new \Exception('Unauthorized to update task status');
        }

        if ($request->has('status') && $request->status_id === 2 && $task->dependencies()->exists()) {

            $incompleteDependencies = $task->dependencies()->where('status_id', '!=', 2)->exists();
            if ($incompleteDependencies) {

                throw new \Exception('Cannot complete task. Dependencies are not completed.');
            }
        }

        $task->update($validatedData);
        return 'Task Updated';
    }

    public function addDependency($taskId,$dependencyId){

        $task = Task::find($taskId);
        $task->dependencies()->attach($dependencyId);
        return 'Dependency created successfully';

    }
}
