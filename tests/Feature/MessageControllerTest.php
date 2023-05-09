<?php

namespace Tests\Feature;

use Tests\TestCase;

use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Message;
use App\Utils\JWTToken;

class MessageControllerTest extends TestCase
{
    public function testAddMessage(): void
    {
        $data = [
            'username' => 'testusernotmember',
            'full_name' => 'Test User',
            'password' => 'password123',
            'membership-status' => 'inactive',
        ];

        $userWithInactiveMembership = User::create($data);

        $message = [
            'title' => 'Test Title',
            'text' => 'Test Description',
            'timestamp' => '2023-01-01 01:01:01',
            'user_id' => $userWithInactiveMembership->id,
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . getenv('TEST_BEARER_TOKEN'),
        ])
        ->postJson('/api/messages/', $message);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('messages', $message);
    }

    public function testGetMessagesWithInactiveMembership()
    {
        $user = User::where('username', 'testusernotmember')->first();

        $token = JWTToken::generateToken($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])
        ->getJson('/api/messages');
    
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'text',
                'timestamp',
                'full_name'
            ]
        ]);
        $response->assertJson(function ($json) {
            foreach ($json as $message) {
                return $message['full_name'] === 'Anonymous';
            }
        });
        
    }

    public function testGetMessagesWithActiveMembership()
    {
        $data = [
            'username' => 'testuser',
            'full_name' => 'Test User',
            'password' => 'password123',
            'membership-status' => 'active',
        ];

        $userWithActiveMembership = User::create($data);

        $token = JWTToken::generateToken($userWithActiveMembership);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])
        ->getJson('/api/messages');
    
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'text',
                'timestamp',
                'full_name'
            ]
        ]);
        $response->assertJson(function ($json) {
            foreach ($json as $message) {
                return $message['full_name'] !== 'Anonymous';
            }
        });
    }

    public function testDeleteMessageSuccessfully()
    {
        $message = Message::where('title', 'Test title')->first();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . getenv('TEST_BEARER_TOKEN'),
        ])
        ->deleteJson('/api/messages/'.$message->id);

        $response->assertStatus(Response::HTTP_OK);
        User::where('username', 'testuser')->delete();
        User::where('username', 'testusernotmember')->delete();
    }

    public function testDeleteMessageWithNonExistentId()
{
    $nonExistentId = 123456789;
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . getenv('TEST_BEARER_TOKEN'),
    ])
    ->deleteJson("/api/messages/{$nonExistentId}");
    $response->assertStatus(404);
    $response->assertJson(['message' => 'Message not found']);
}


}
