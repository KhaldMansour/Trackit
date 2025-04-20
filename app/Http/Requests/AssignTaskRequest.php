<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="AssignTaskRequest",
 *     title="Assign Task Request",
 *     description="Payload to assign a task to a user",
 *     type="object",
 *     required={"assignee_id"},
 *     @OA\Property(
 *         property="assignee_id",
 *         type="integer",
 *         example=3,
 *         description="The ID of the user to assign the task to"
 *     )
 * )
 */
class AssignTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'assignee_id' => 'required|exists:users,id',
        ];
    }
}
