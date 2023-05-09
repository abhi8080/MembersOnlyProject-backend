<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Http\Response;
use App\Models\User;

class UserControllerTest extends TestCase
{
    public function testAddUser(): void
    {
        $data = [
            'username' => 'testuser',
            'full_name' => 'Test User',
            'password' => 'password123',
            'membership_status' => 'inactive',
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', ['username' => 'testuser']);
    }

    public function testLogin()
    {
        $data = [
            'username' => 'testuser',
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(['id', 'jwt']);
    }

    
    public function testUpdateUser()
    {
        $user = User::where('username', 'testuser')->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . getenv('TEST_BEARER_TOKEN'),
        ])
        ->putJson('/api/user/'.$user->id);
    $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'membership-status' => 'active'
        ]);
    }

    public function testGetUserAdminStatus()
    {
        $user = User::where('username', 'testuser')->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . getenv('TEST_BEARER_TOKEN'),
        ])
        ->getJson('/api/user/'.$user->id);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['isAdmin' => 0]);
        User::where('username', 'testuser')->delete();

    }

}
