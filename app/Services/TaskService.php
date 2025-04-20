<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Exception;

class TaskService
{
    public function __construct(private readonly TaskRepository $taskRepository)
    {
    }

    public function getAll(int $limit = 10)
    {
        return $this->taskRepository->with('assignee')->paginate($limit);
    }

    public function createTask($data)
    {
        $task = $this->taskRepository->create($data);

        if (!empty($data['dependency_task_ids'])) {
            $task->dependencies()->attach($data['dependency_task_ids']);
        }

        return $task;
    }

    public function updateTask(Task $task, array $data)
    {
        $task = $this->taskRepository->update($data, $task->id);

        if (isset($data['dependency_task_ids'])) {
            $task->dependencies()->sync($data['dependency_task_ids']);
        }

        return $task;
    }

    public function deleteTask(Task $task)
    {
        try {
            $task->delete();

            return true;
        } catch (Exception $e) {
            throw new Exception("Error deleting task: " . $e->getMessage());
        }
    }

    public function changeTaskStatus(Task $task, array $data)
    {
        $task = $this->taskRepository->update($data, $task->id);

        return $task;
    }

    public function myTasks($limit = 10)
    {
        $user = auth()->user();

        $myTasks = $this->taskRepository->where('assignee_id', $user->id)->paginate($limit);

        return $myTasks;
    }

    public function assignTask(Task $task, array $data)
    {
        $task = $this->taskRepository->update($data, $task->id);

        return $task;
    }
}
