<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="UpdateTaskRequest",
 *     type="object",
 *     title="Update Task Request",
 *     description="Data allowed for updating a task",
 *     @OA\Property(property="title", type="string", example="Updated task title"),
 *     @OA\Property(property="description", type="string", example="Updated task description"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "canceled"}, example="completed"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-06-01"),
 *     @OA\Property(property="assignee_id", type="integer", example=2),
 *     @OA\Property(property="dependency_task_id", type="integer", example=5)
 * )
 */
class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255',
                'unique:tasks,title,' . $this->task->id,
            ],
            'description' => 'nullable|string',
            'status' => ['sometimes', Rule::in(Task::getStatuses())],
            'due_date' => 'nullable|date',
            'assignee_id' => 'nullable|exists:users,id', 'different:id',
            'dependency_task_ids' => 'nullable|array',
            'dependency_task_ids.*' => 'nullable|numeric|exists:tasks,id',
        ];
    }
}
