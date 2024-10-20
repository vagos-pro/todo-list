<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskSearchRequest;
use App\Http\Requests\TaskFilterRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="Get list of tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="is_completed",in="query",description="Filter by completion status",required=false,@OA\Schema(type="boolean")),
     *     @OA\Parameter(name="sort",in="query",description="Sort by field (is_completed or created_at)",required=false,@OA\Schema(type="string")),
     *     @OA\Parameter(name="direction",in="query",description="Sorting direction (asc or desc)",required=false,@OA\Schema(type="string")),
     *     @OA\Parameter(name="perPage",in="query",description="Number of tasks per page for pagination",required=false,@OA\Schema(type="integer", example=15)),
     *     @OA\Response(response=200,description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TaskResource"),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=10),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="total", type="integer", example=150)
     *         )
     *     )
     * )
     *
     * Display a listing of the resource.
     *
     * @param TaskFilterRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(TaskFilterRequest $request): AnonymousResourceCollection
    {
        $tasks = $this->taskService->getFilteredTasks($request);
        return TaskResource::collection($tasks);
    }

    /**
     * Store a new task.
     *
     * @param TaskStoreRequest $request
     * @return TaskResource
     */
    public function store(TaskStoreRequest $request): TaskResource
    {
        $user = auth()->user();
        $task = $this->taskService->createTask($request->validated(), $user);
        return new TaskResource($task);
    }

    /**
     * Display the specified task.
     *
     * @param Task $task
     * @return TaskResource
     */
    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    /**
     * Update the task.
     *
     * @param TaskUpdateRequest $request
     * @param Task $task
     * @return TaskResource
     */
    public function update(TaskUpdateRequest $request, Task $task): TaskResource
    {
        $task = $this->taskService->updateTask($task, $request->validated());
        return new TaskResource($task);
    }

    /**
     * Remove the task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }

    /**
     * Search the tasks.
     *
     * @param TaskSearchRequest $request
     * @return AnonymousResourceCollection
     */
    public function search(TaskSearchRequest $request): AnonymousResourceCollection
    {
        $tasks = $this->taskService->searchTasks($request);
        return TaskResource::collection($tasks);
    }
}
