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
        ], 200);
    }

    public function show($id): ?object
    {
        $task = $this->taskService->findById($id);

        if (!$task) return response()->json(['error' => 'Task not found'], 404);

        return response()->json([
            'data' => $task,
            'success' => true
        ], 200);
    }

    public function store(StoreTaskRequest $request): object
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $task = $this->taskService->createTask($data);

        if (!$task) return response()->json(['error' => 'Task not created'], 500);

        return response()->json([
            'message' => 'Task created successfully',
            'success' => true
        ], 201);
    }

    public function update(UpdateTaskRequest $request, $id): object
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['updated_at'] = now();

        $response = $this->taskService->updateTask($data, $id);

        if (!$response) return response()->json(['error' => 'Task not updated'], 500);

        return response()->json([
            'message' => 'Task updated successfully',
            'success' => true
        ], 200);
    }

    public function destroy($id): object
    {
        $response = $this->taskService->deleteTask($id);

        if (!$response) return response()->json(['error' => 'Task not found'], 404);

        return response()->json([
            'message' => 'Task deleted successfully',
            'success' => true
        ], 204);
    }
}
