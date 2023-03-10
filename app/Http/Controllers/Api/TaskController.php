<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Support\Facades\App;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct()
    {
        $this->taskService = App::make(TaskService::class);
    }

    public function index(): ?object
    {
        $tasks = $this->taskService->getAll();

        if ($tasks->isEmpty()) return response()->json(['error' => 'No tasks found'], 404);

        return response()->json([
            'data' => $tasks,
            'success' => true
        ]);
    }

    public function show($id): ?object
    {
        $task = $this->taskService->findById($id);

        if (!$task) return response()->json(['error' => 'Task not found'], 404);

        return response()->json([
            'data' => $task,
            'success' => true
        ]);
    }

    public function store(StoreTaskRequest $request): object
    {
        $data = $request->validated();

        $task = $this->taskService->createTask($data);

        if (!$task) return response()->json(['error' => 'Task not created'], 500);

        return response()->json([
            'message' => 'Task created successfully',
            'success' => true
        ]);
    }

    public function update(UpdateTaskRequest $request, $id): object
    {
        $data = $request->validated();

        $response = $this->taskService->updateTask($data, $id);

        if (!$response) return response()->json(['error' => 'Task not updated'], 500);

        return response()->json([
            'message' => 'Task updated successfully',
            'success' => true
        ]);
    }

    public function destroy($id): object
    {
        $response = $this->taskService->deleteTask($id);

        if (!$response) return response()->json(['error' => 'Task not found'], 404);

        return response()->json([
            'message' => 'Task deleted successfully',
            'success' => true
        ]);
    }
}
