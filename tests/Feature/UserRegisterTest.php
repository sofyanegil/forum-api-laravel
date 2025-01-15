<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // Arrange
        $payload = [
            'fullname' => 'John Doe',
            'username' => 'john_doe',
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/users', $payload);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'fullname' => 'John Doe',
            'username' => 'john_doe',
        ]);
        $this->assertDatabaseHas('users', [
            'username' => 'john_doe',
        ]);
        $user = User::where('username', 'john_doe')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_user_cant_register_with_exist_username()
    {
        // Arrange
        User::create([
            'fullname' => 'Jane Doe',
            'username' => 'john_doe',
            'password' => Hash::make('password123'),
        ]);

        $payload = [
            'fullname' => 'John Doe',
            'username' => 'john_doe', // Same username as the existing user
            'password' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/users', $payload);

        // Assert
        $response->assertStatus(400);
        $response->assertJsonValidationErrors('username');
    }

    public function test_user_cant_register_with_bad_payload()
    {
        // Arrange
        $payloadWithOutPassword = [
            'fullname' => 'John Doe',
            'username' => 'john_doe',
        ];

        $payloadWithoutUsername = [
            'fullname' => 'John Doe',
            'password' => 'password123',
        ];

        // Act
        $responseWithoutPassword = $this->postJson('/api/users', $payloadWithOutPassword);
        $responseWithoutUsername = $this->postJson('/api/users', $payloadWithoutUsername);

        // Assert
        $responseWithoutPassword->assertStatus(400);
        $responseWithoutPassword->assertJsonValidationErrors('password');

        $responseWithoutUsername->assertStatus(400);
        $responseWithoutUsername->assertJsonValidationErrors('username');
    }
}
