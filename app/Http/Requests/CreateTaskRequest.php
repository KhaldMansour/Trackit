<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="CreateTaskRequest",
 *     type="object",
 *     title="Create Task Request",
 *     description="Data required to create a new task",
 *     required={"title", "status"},
 *     @OA\Property(property="title", type="string", example="Create API documentation"),
 *     @OA\Property(property="description", type="string", example="Write Swagger docs for task creation"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "canceled"}, example="pending"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-05-20"),
 *     @OA\Property(property="assignee_id", type="integer", example=1),
 *     @OA\Property(
 *         property="dependency_task_ids",
 *         type="array",
 *         @OA\Items(type="integer", example=2),
 *         description="Array of task IDs this task depends on"
 *     )
 * )
 */
class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:tasks,title',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(Task::getStatuses())],
            'due_date' => 'nullable|date',
            'assignee_id' => 'sometimes|numeric|exists:users,id',
            'dependency_task_ids' => 'nullable|array',
            'dependency_task_ids.*' => 'nullable|numeric|exists:tasks,id',
        ];
    }
}
