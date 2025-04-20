<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\ChangeStatusRequest;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Get a list of tasks with optional filters.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="filters",
     *         in="query",
     *         description="Query parameters for filtering the tasks.",
     *         required=false,
     *         @OA\Schema(ref="#/components/schemas/ListTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of tasks.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="tasks fetched successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/TaskResource")
     *             )
     *         )
     *     ),
     * )
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $task = $this->taskService->getAll($limit);

        // return $task;

        return ApiResponse::send(TaskResource::collection($task)->response()->getData(), 'Task created successfully', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function create(CreateTaskRequest $request)
    {
        $request->user()->cannot('create', Task::class) && throw new AuthorizationException('This action is unauthorized.');

        $task = $this->taskService->createTask($request->validated());

        return ApiResponse::send(new TaskResource($task), 'Task created successfully', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TaskResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $request->user()->cannot('update', $task) && throw new AuthorizationException('This action is unauthorized.');

        $task = $this->taskService->updateTask($task, $request->validated());

        return ApiResponse::send(new TaskResource($task), 'Task updated successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/{task}",
     *     summary="Get a single task by ID",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID of the task to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task showed successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Task showed successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/TaskResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function show(Task $task, Request $request)
    {
        $request->user()->cannot('show', $task) && throw new AuthorizationException('This action is unauthorized.');

        return ApiResponse::send(new TaskResource($task), 'Task showed successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tasks/{task}",
     *     summary="Delete a task",
     *     description="Deletes a task and its associated dependencies if applicable.",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID of the task to delete",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function delete(Task $task, Request $request)
    {
        $request->user()->cannot('delete', $task) && throw new AuthorizationException('This action is unauthorized.');

        $this->taskService->deleteTask($task);

        return ApiResponse::send(null, 'Task deleted successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{task}/change-status",
     *     operationId="changeStatus",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     summary="Change the status of a task",
     *     description="Updates the status of a task to one of the allowed values",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID of the task to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="The status data",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ChangeStatusRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Task status updated successfully"),
     *             @OA\Property(property="data", type="object", description="Updated task data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid status provided")
     *         )
     *     )
     * )
     */
    public function changeStatus(Task $task, ChangeStatusRequest $request)
    {
        $request->user()->cannot('changeStatus', $task) && throw new AuthorizationException('This action is unauthorized.');

        $task = $this->taskService->changeTaskStatus($task, $request->validated());

        return ApiResponse::send(new TaskResource($task), 'Task status updated successfully');
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/my-tasks",
     *     summary="Retrieve tasks assigned to the authenticated user",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of tasks assigned to the user",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="My Tasks"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TaskResource"))
     *         )
     *     )
     * )
     */
    public function myTasks()
    {
        $myTasks = $this->taskService->myTasks();

        return ApiResponse::send(TaskResource::collection($myTasks), 'My assigned tasks');
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{task}/assign",
     *     summary="Assign a task to a user",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="The ID of the task to assign",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AssignTaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task successfully assigned",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Task assigned successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function assign(AssignTaskRequest $request, Task $task)
    {
        $request->user()->cannot('assign', $task) && throw new AuthorizationException('This action is unauthorized.');

        $task = $this->taskService->assignTask($task, $request->validated());

        return ApiResponse::send(new TaskResource($task), 'Task assigned to a user successfully');
    }
}
