<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="TaskResource",
 *     @OA\Property(property="id", type="integer", description="The ID of the task", example=1),
 *     @OA\Property(property="title", type="string", description="The title of the task", example="Buy groceries"),
 *     @OA\Property(property="description", type="string", description="The description of the task", example="Buy milk, bread, and eggs from the store"),
 *     @OA\Property(property="completed", type="boolean", description="Indicates whether the task is completed", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="The creation timestamp of the task", example="2024-10-10 12:00:30"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="The last update timestamp of the task", example="2024-10-10 12:00:30")
 * )
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
