<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TaskRepository
{
    public function getAll(): ?object
    {
        $tasks = DB::connection('mysql')
                ->table('tasks')
                    ->where('user_id', auth()->user()->id)
                        ->get();

        return $tasks;
    }

    public function findById(int $id): ?object
    {
        $task = DB::connection('mysql')
                ->table('tasks')
                    ->where('id', $id)
                        ->where('user_id', auth()->user()->id)
                            ->first();

        return $task;
    }

    public function createTask(array $data): bool
    {
        $task = DB::connection('mysql')
                ->table('tasks')
                    ->insert($data);

        return $task;
    }

    public function updateTask(array $data, int $id): bool
    {
        $task = DB::connection('mysql')
                ->table('tasks')
                    ->where('id', $id)
                        ->where('user_id', auth()->user()->id)
                            ->update($data);

        return $task;
    }

    public function deleteTask(int $id): bool
    {
        $task = DB::connection('mysql')
                ->table('tasks')
                    ->where('id', $id)
                        ->where('user_id', auth()->user()->id)
                            ->delete();

        return $task;
    }
}
