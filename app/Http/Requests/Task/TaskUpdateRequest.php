<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TaskUpdateRequest",
 *     @OA\Property(property="title", type="string", description="The updated title of the task", example="Buy groceries and vegetables"),
 *     @OA\Property(property="description", type="string", description="The updated description of the task", example="Buy milk, bread, eggs, and carrots"),
 *     @OA\Property(property="completed", type="boolean", description="Indicates whether the task is completed", example=true)
 * )
 */
class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'completed' => 'sometimes|boolean',
        ];
    }
}
