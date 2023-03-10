<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\App;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->taskRepository = App::make(TaskRepository::class);
    }

    public function getAll(): ?object
    {
        return $this->taskRepository->getAll();
    }

    public function findById(int $id): ?object
    {
        return $this->taskRepository->findById($id);
    }

    public function createTask(array $data): bool
    {
        $data['user_id'] = auth()->user()->id;

        return $this->taskRepository->createTask($data);
    }

    public function updateTask(array $data, int $id): bool
    {
        $data['user_id'] = auth()->user()->id;

        $task = $this->taskRepository->findById($id);

        if (!$task) return false;

        return $this->taskRepository->updateTask($data, $id);
    }

    public function deleteTask(int $id): bool
    {
        $task = $this->taskRepository->findById($id);

        if (!$task) return false;

        return $this->taskRepository->deleteTask($id);
    }
}
