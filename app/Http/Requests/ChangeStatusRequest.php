<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="ChangeStatusRequest",
 *     type="object",
 *     title="Change Status Request",
 *     description="Data required to change the status of an existing task",
 *     required={"status"},
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "canceled"}, example="completed"),
 * )
 */
class ChangeStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Add any authorization logic here (return true if authorized)
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,completed,canceled', // Status must be one of these values
        ];
    }
}
