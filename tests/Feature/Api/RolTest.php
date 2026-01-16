<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Rol;
use App\Models\Permiso;

class RolTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_list_of_roles()
    {
        $user = User::factory()->create();
        Rol::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/roles');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_store_creates_new_role()
    {
        $user = User::factory()->create();
        $permiso = Permiso::factory()->create();

        $data = [
            'nombre' => 'New Role',
            'descripcion' => 'Description',
            'permisos' => [$permiso->id_permiso]
        ];

        $response = $this->actingAs($user)->postJson('/api/roles', $data);

        $response->assertStatus(201)
                 ->assertJson(['nombre' => 'New Role']);

        $this->assertDatabaseHas('roles', ['nombre' => 'New Role']);
    }
}
