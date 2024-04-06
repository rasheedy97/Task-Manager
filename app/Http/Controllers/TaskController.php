<?php

namespace App\Http\Controllers;

use App\Http\Requests\DependencyRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $taskService;
    public function __construct(TaskService $taskService)
    {

        $this->taskService = $taskService;
        $this->middleware('role:Manager')->only(['store', 'destroy','addDependency']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $tasks = $this->taskService->getAllTasks($request);
        return response()->json($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $message = $this->taskService->createTask($request);

        return response()->json(["message" => $message], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task = $this->taskService->getTask($task);
        return response()->json(["Task" => $task], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        $response = $this->taskService->updateTask($request,$task);
       return response()->json(["message" => $response], 200);



    }

    /**
     * Add task dependency to a task
     */
    public function addDependency(DependencyRequest $request)
    {

        $response = $this->taskService->addDependency($request->task_id,$request->dependency_id);
        return response()->json(["message" => $response], 200);


    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task Deleted']);
    }
}
