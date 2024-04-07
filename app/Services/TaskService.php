<?php

namespace App\Services;

use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
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

        $query->when($request->filled('status_id'), function ($query) use ($request) {

            $query->whereIn('status_id', (array)$request->input('status_id'));

        });

        $query->when($request->filled('assignee_id') && $user->hasRole('Manager'), function ($query) use ($request) {
            $query->whereIn('assignee_id', (array)$request->input('assignee_id'));
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
                throw new \Exception('Unauthorized to view task',401);
            }
        }
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

    public function updateTask(UpdateTaskRequest $request, Task $task)
    {

//return $request->input('status_id');

        if (Auth::user()->hasRole('Manager')) {
           return $this->updateTaskForManager($request, $task);
        } else {
         return   $this->updateTaskForUser($request, $task);
        }

    }


    protected function updateTaskForManager(UpdateTaskRequest $request, Task $task)
    {
        $user = Auth::user();
        $task->update($request->validated());
        return 'Task Updated Successfully';
    }

    protected function updateTaskForUser(UpdateTaskRequest $request, Task $task)
    {
        $user = Auth::user();

        if ($task->assignee_id === $user->id) {

            if ($request->status_id == 2) {

                $dependencies = $task->dependencies;
                foreach ($dependencies as $dependency) {
                    if ($dependency->status_id === 1) {
                        throw new \Exception('Cannot complete task until all dependencies are completed',400);
                    }
                }
            }

            $task->update([
                'status_id' => $request->status_id,
            ]);
        } else {
            throw new \Exception('You are not authorized to update this task',401);
        }
        return 'Task updated successfuly';

    }

    public function addDependency($taskId,$dependencyId){

        $task = Task::find($taskId);
        $task->dependencies()->attach($dependencyId);
        return 'Dependency created successfully';

    }
}
