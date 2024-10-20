<?php

namespace App\Services;

use App\Http\Requests\Task\TaskSearchRequest;
use App\Http\Requests\TaskFilterRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getFilteredTasks(TaskFilterRequest $request): LengthAwarePaginator
    {
        $query = Task::query();

        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->boolean('is_completed'));
        }

        if ($request->has('sort')) {
            $sortField = $request->get('sort', 'created_at');
            $query->orderBy($sortField, $request->get('direction', 'asc'));
        }

        $perPage = $request->get('perPage', 10);
        return $query->paginate($perPage);
    }

    public function createTask(array $data, User $user): \Illuminate\Database\Eloquent\Model
    {
        return $user->tasks()->create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function searchTasks(TaskSearchRequest $request): LengthAwarePaginator
    {
        $query = $request->input('query');
        $perPage = $request->input('perPage', 15);

        return Task::search($query)->paginate($perPage);
    }
}
