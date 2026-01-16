<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_list_of_users()
    {
        $user = User::factory()->create();
        User::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/usuarios');

        $response->assertStatus(200)
                 ->assertJsonCount(4);
    }

    public function test_store_creates_new_user()
    {
        $user = User::factory()->create();

        $data = [
            'nombre' => 'New User',
            'correo' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'activo' => true
        ];

        $response = $this->actingAs($user)->postJson('/api/usuarios', $data);

        $response->assertStatus(201)
                 ->assertJson(['nombre' => 'New User']);

        $this->assertDatabaseHas('usuarios', ['correo' => 'new@example.com']);
    }

    public function test_update_modifies_user()
    {
        $user = User::factory()->create();
        $target = User::factory()->create();

        $data = [
            'nombre' => 'Updated Name'
        ];

        $response = $this->actingAs($user)->putJson("/api/usuarios/{$target->id_usuario}", $data);

        $response->assertStatus(200)
                 ->assertJson(['nombre' => 'Updated Name']);

        $this->assertDatabaseHas('usuarios', ['id_usuario' => $target->id_usuario, 'nombre' => 'Updated Name']);
    }

    public function test_destroy_deactivates_user()
    {
        $user = User::factory()->create();
        $target = User::factory()->create(['activo' => true]);

        $response = $this->actingAs($user)->deleteJson("/api/usuarios/{$target->id_usuario}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('usuarios', ['id_usuario' => $target->id_usuario, 'activo' => false]);
    }
}
