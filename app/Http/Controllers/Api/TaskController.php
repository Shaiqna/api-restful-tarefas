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

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get all tasks",
     *     description="Returns a list of all tasks",
     *     security={ {"bearerAuth":{}} },
     *     tags={"Tasks"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                  @OA\Items(ref="#/components/schemas/Task")
     *             ),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No tasks found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

    public function index(): ?object
    {
        $tasks = $this->taskService->getAll();

        if ($tasks->isEmpty()) return response()->json(['error' => 'No tasks found'], 404);

        return response()->json([
            'data' => $tasks,
            'success' => true
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get a task by ID",
     *     description="Returns a single task by ID",
     *     tags={"Tasks"},
     *     security={ {"bearerAuth":{}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the task to return",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="title", type="string", example="Minha tarefa"),
     *              @OA\Property(property="description", type="string", example="Descrição da minha tarefa"),
     *              @OA\Property(property="completed", type="integer", example=0),
     *              @OA\Property(property="user_id", type="integer", example=1)
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

    public function show($id): ?object
    {
        $task = $this->taskService->findById($id);

        if (!$task) return response()->json(['error' => 'Task not found'], 404);

        return response()->json([
            'data' => $task,
            'success' => true
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/create/task",
     *     summary="Cria nova tarefa",
     *     description="Cria nova tarefa",
     *     tags={"Tasks"},
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Minha tarefa"),
     *                 @OA\Property(property="description", type="string", example="Descrição da minha tarefa"),
     *                 @OA\Property(property="completed", type="integer", example=0),
     *                 @OA\Property(property="user_id", type="integer", example=1)
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa Criada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="success", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Tarefa não criada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string"),
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Put(
     *     path="/api/update/task/{id}",
     *     summary="Atualiza uma tarefa pelo ID",
     *     description="Atualiza uma tarefa pelo ID",
     *     tags={"Tasks"},
     *     security={ {"bearerAuth":{}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa a ser atualizada",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     security={{ "bearerAuth": {} }},
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="Minha tarefa"),
     *                 @OA\Property(property="description", type="string", example="Descrição da minha tarefa"),
     *                 @OA\Property(property="completed", type="integer", example=0),
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="success", type="boolean")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Tarefa não atualizada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Delete(
     *     path="/api/delete/task/{id}",
     *     summary="Deleta uma tarefa pelo ID",
     *     description="Delete uma tarefa pelo ID",
     *     tags={"Tasks"},
     *     security={ {"bearerAuth":{}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da tarefa a ser deletada",
     *         @OA\Schema(type="integer", format="int64")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Tarefa deletada com sucesso",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarefa não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */

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
