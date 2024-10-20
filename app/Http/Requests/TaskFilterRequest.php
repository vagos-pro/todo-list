<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="TaskFilterRequest",
 *     @OA\Property(property="completed", type="boolean", description="Filter tasks by completion status", example=true),
 *     @OA\Property(property="sort", type="string", enum={"status", "created_at"}, description="Sort tasks by field (status or created_at)", example="created_at"),
 *     @OA\Property(property="direction", type="string", enum={"asc", "desc"}, description="Sorting direction", example="asc"),
 *     @OA\Property(property="perPage", type="integer", description="Number of tasks per page for pagination", example=15)
 * )
 */
class TaskFilterRequest extends FormRequest
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
            'is_completed' => 'sometimes|in:true,false,1,0',
            'perPage' => 'sometimes|integer|min:1|max:100',
            'sort' => 'sometimes|in:is_completed,created_at',
            'direction' => 'sometimes|in:asc,desc',
        ];
    }
}
