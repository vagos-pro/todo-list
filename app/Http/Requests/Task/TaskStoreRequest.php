<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;


/**
 * @OA\Schema(
 *     schema="TaskStoreRequest",
 *     required={"title"},
 *     @OA\Property(property="title", type="string", description="The title of the task", example="Buy groceries"),
 *     @OA\Property(property="description", type="string", description="The description of the task", example="Buy milk, bread, and eggs"),
 *     @OA\Property(property="is_completed", type="boolean", description="Indicates whether the task is completed", example=false)
 * )
 */
class TaskStoreRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
