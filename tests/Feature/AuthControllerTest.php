<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_register_a_new_user(): void
    {
        $taskData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $this->faker->password,
        ];
        $response = $this->postJson('/api/register', $taskData);
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'name' => $taskData['name'],
                    'email' => $taskData['email'],
                ]
            ]);
        $this->assertDatabaseHas('users', [
            'email' => $response->json('data.email'),
        ]);
    }

    public function test_can_get_validation_errors_on_register_a_new_user()
    {
        $response = $this->postJson('/api/register');
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'name',
                    'email',
                    'password',
                ]
            ]);
    }

    public function test_can_login_as_user()
    {
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password;

        User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password,
        ]);
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                ]
            ]);
    }

    public function test_cannot_login_with_invalid_credentials()
    {
        $email = $this->faker->unique()->safeEmail;
        $password = $this->faker->password;

        User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this
            ->postJson('/api/login', [
                'email' => $email,
                'password' => $this->faker->password,
            ])
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Invalid login credentials',
            ]);
    }

    public function test_can_logout_a_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth-token')->plainTextToken;

        $this
            ->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/logout')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Logged out successfully',
            ]);
    }

    public function test_cannot_logout_without_authentication()
    {
        $response = $this->postJson('/api/logout');
        $response
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
