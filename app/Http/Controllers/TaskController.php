<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskFilterRequest;
use App\Http\Requests\Task\TaskSearchRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

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
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(required=true,@OA\JsonContent(ref="#/components/schemas/TaskStoreRequest")),
     *     @OA\Response(response=201,description="Task created successfully",@OA\JsonContent(ref="#/components/schemas/TaskResource"))
     * )
     *
     * Store a new task.
     *
     * @param TaskStoreRequest $request
     * @return TaskResource
     */
    public function store(TaskStoreRequest $request): TaskResource
    {
        $task = $this->taskService->createTask($request->validated(), auth()->user());
        return new TaskResource($task);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     summary="Get a specific task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id",in="path",description="Task ID",required=true,@OA\Schema(type="integer")),
     *     @OA\Response(response=200,description="Successful operation",@OA\JsonContent(ref="#/components/schemas/TaskResource"))
     * )
     *
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
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id",in="path",description="Task ID",required=true,@OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true,@OA\JsonContent(ref="#/components/schemas/TaskUpdateRequest")),
     *     @OA\Response(response=200,description="Task updated successfully",@OA\JsonContent(ref="#/components/schemas/TaskResource"))
     * )
     *
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
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id",in="path",description="Task ID",required=true,@OA\Schema(type="integer")),
     *     @OA\Response(response=204,description="Task deleted successfully")
     * )
     *
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
     * @OA\Get(
     *     path="/api/tasks/search",
     *     summary="Search tasks",
     *     tags={"Tasks"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="query",in="query",description="Search query string",required=true,@OA\Schema(type="string")),
     *     @OA\Parameter(name="perPage",in="query",description="Number of tasks per page for pagination",required=false,@OA\Schema(type="integer", example=15)),
     *     @OA\Response(response=200,description="Search results",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/TaskResource")),
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="per_page", type="integer", example=15),
     *             @OA\Property(property="total", type="integer", example=75)
     *         )
     *     )
     * )
     *
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
