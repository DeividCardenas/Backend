<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comite;

class ComiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_list_of_comites()
    {
        $user = User::factory()->create();
        Comite::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/comites');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_store_creates_new_comite()
    {
        $user = User::factory()->create();
        $responsable = User::factory()->create();

        $data = [
            'nombre' => 'New Committee',
            'objetivo' => 'Test Goal',
            'responsable_id' => $responsable->id_usuario,
            'miembros' => [$user->id_usuario]
        ];

        $response = $this->actingAs($user)->postJson('/api/comites', $data);

        $response->assertStatus(201)
                 ->assertJson(['nombre' => 'New Committee']);

        $this->assertDatabaseHas('comites', ['nombre' => 'New Committee']);
    }
}
