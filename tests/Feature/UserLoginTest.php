<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        // Arrange
        User::create([
            'username' => 'john_doe',
            'fullname' => 'John Doe',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'username' => 'john_doe',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/authentications', $payload);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                "accessToken",
                "refreshToken",
            ]
        ]);
    }

    public function test_user_cant_login_with_invalid_credentials()
    {
        // Arrange
        User::create([
            'username' => 'john_doe',
            'fullname' => 'John Doe',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'username' => 'john_doe',
            'password' => 'password1234', // Invalid password
        ];

        // Act
        $response = $this->postJson('/api/authentications', $payload);

        // Assert
        $response->assertStatus(401);
        $response->assertUnauthorized();
        $response->assertJson([
            'status' => 'fail',
            'code' => 401,
            'message' => 'Username or password is incorrect',
        ]);
    }
}
