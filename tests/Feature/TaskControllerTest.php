<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Laravel\Scout\Scout;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_list_tasks()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        $paginationStructure = include(base_path('tests/Fixtures/PaginationJsonStructure.php'));

        $response = $this->getJson('/api/tasks');
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure(array_merge([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'is_completed',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ], $paginationStructure));
    }

    public function test_can_create_task()
    {
        $taskData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
        $response = $this->postJson('/api/tasks', $taskData);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'user_id' => $this->user->id,
            ]);
        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function test_can_show_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'is_completed' => $task->is_completed,
                ],
            ]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $updatedData = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'is_completed' => $this->faker->boolean,
        ];
        $response = $this->putJson("/api/tasks/{$task->id}", $updatedData);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'id' => $task->id,
                'title' => $updatedData['title'],
                'description' => $updatedData['description'],
                'is_completed' => $updatedData['is_completed'],
            ]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description'],
            'is_completed' => $updatedData['is_completed']
        ]);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);
        $response = $this->deleteJson("/api/tasks/{$task->id}");
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Task deleted successfully',
            ]);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_can_search_tasks()
    {
        $searchTitle = $this->faker->sentence;

        Task::factory()->create(['title' => $searchTitle, 'user_id' => $this->user->id]);
        Task::factory()->create(['title' => $this->faker->sentence, 'user_id' => $this->user->id]);

        // Чтобы индекс успел обновиться
        usleep(50000);

        $response = $this->getJson("/api/tasks/search?query={$searchTitle}");
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['title' => $searchTitle])
            ->assertJsonMissing(['title' => 'Another Task']);
    }
}
