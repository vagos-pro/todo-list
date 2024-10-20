<?php

namespace App\Services;

use App\Http\Requests\Task\TaskFilterRequest;
use App\Http\Requests\Task\TaskSearchRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected int $defaultPerPage = 10;

    public function getFilteredTasks(TaskFilterRequest $request): LengthAwarePaginator
    {
        $query = Task::query()->where('user_id', Auth::id());

        if ($request->has('is_completed')) {
            $query->where('is_completed', $request->boolean('is_completed'));
        }

        if ($request->has('sort')) {
            $sortField = $request->get('sort', 'created_at');
            $query->orderBy($sortField, $request->get('direction', 'asc'));
        }

        $perPage = $request->get('perPage', $this->defaultPerPage);
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
        $perPage = $request->input('perPage', $this->defaultPerPage);

        return Task::search($query)->paginate($perPage);
    }
}
