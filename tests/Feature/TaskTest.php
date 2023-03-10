<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutEvents;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\App;
use stdClass;

class TaskTest extends TestCase
{
    protected $user;
    protected $token;

    use WithoutEvents;

    public function testSuccessCreateTask()
    {
        $data = [
            'title' => 'Nova Tarefa',
            'description' => 'Descrição da Nova Tarefa',
            'completed' => 0,
            'user_id' => 8,
        ];

        $function = [
            'createTask' => true,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->createTask($data);

        $this->assertEquals($response, true);
    }

    public function testErrorCreateTaskWithoutData()
    {
        $data = [
            'description' => 'Descrição da Nova Tarefa',
            'completed' => 0,
            'user_id' => 8,
        ];

        $function = [
            'createTask' => false,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->createTask($data);

        $this->assertEquals($response, false);
    }

    public function testErrorNotFoundTask()
    {
        $function = [
            'findById' => null,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->findById(1);

        $this->assertEquals($response, null);
    }

    public function testSuccessUpdateTask()
    {
        $task = new stdClass();
        $task->id = 1;
        $task->title = 'Nova Tarefa';
        $task->description = 'Descrição da Nova Tarefa';
        $task->completed = 0;
        $task->user_id = 8;

        $data = [
            'title' => 'Nova Tarefa Atualizada',
            'description' => 'Descrição da Nova Tarefa',
            'completed' => 0,
            'user_id' => 8,
        ];

        $function = [
            'updateTask' => true,
            'findById' => $task,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->updateTask($data, 1);

        $this->assertEquals($response, true);
    }

    public function testErrorUpdateTaskWithoutData()
    {
        $task = new stdClass();
        $task->id = 1;
        $task->title = 'Nova Tarefa';
        $task->description = 'Descrição da Nova Tarefa';
        $task->completed = 0;
        $task->user_id = 8;

        $data = [
            'description' => 'Descrição da Nova Tarefa',
            'completed' => 0,
            'user_id' => 8,
        ];

        $function = [
            'updateTask' => false,
            'findById' => $task,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->updateTask($data, 1);

        $this->assertEquals($response, false);
    }

    public function testSuccesDeleteTask()
    {
        $task = new stdClass();
        $task->id = 1;
        $task->title = 'Nova Tarefa';
        $task->description = 'Descrição da Nova Tarefa';
        $task->completed = 0;
        $task->user_id = 8;

        $function = [
            'deleteTask' => true,
            'findById' => $task,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->deleteTask(1);

        $this->assertEquals($response, true);
    }

    public function testErrorDeleteTask()
    {
        $task = new stdClass();
        $task->id = 1;
        $task->title = 'Nova Tarefa';
        $task->description = 'Descrição da Nova Tarefa';
        $task->completed = 0;
        $task->user_id = 8;

        $function = [
            'deleteTask' => false,
            'findById' => $task,
        ];

        $this->instance(TaskRepository::class, $this->makeMockTaskRepository($function));

        $taskService = App::make(TaskService::class);

        $response = $taskService->deleteTask(1);

        $this->assertEquals($response, false);
    }

    private function makeMockTaskRepository($functionsAndReturns){
        $mock = Mockery::mock(TaskRepository::class);
        $mock->shouldReceive($functionsAndReturns);

        return $mock;
    }
}
