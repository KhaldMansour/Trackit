<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ListTaskRequest",
 *     type="object",
 *     title="List Task Request",
 *     description="Request parameters to list tasks with search and limit",
 *     @OA\Property(property="limit", type="integer", example=10, description="Number of tasks per page (1-100)"),
 *     @OA\Property(property="search", type="string", example="assignee.name:khaled;status:pending;due_date:2025-05-25,2025-06-01", description="Search filter for tasks, supports various field filters."),
 * )
 */
class ListTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'limit' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'limit.integer' => 'Limit must be an integer.',
            'limit.min' => 'Limit must be at least 1.',
            'limit.max' => 'Limit can be at most 100.',
            'search.string' => 'Search must be a string.',
        ];
    }
}
