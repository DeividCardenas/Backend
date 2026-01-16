<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComiteRequest;
use App\Http\Requests\UpdateComiteRequest;

class ComiteController extends Controller
{
    public function index()
    {
        $comites = Comite::with('responsable', 'miembros')->get();
        return response()->json($comites);
    }

    public function store(StoreComiteRequest $request)
    {
        $validated = $request->validated();

        $comite = Comite::create([
            'nombre' => $validated['nombre'],
            'objetivo' => $validated['objetivo'],
            'responsable_id' => $validated['responsable_id'] ?? null,
        ]);

        if (isset($validated['miembros'])) {
            $comite->miembros()->attach($validated['miembros']);
        }

        return response()->json($comite->load('responsable', 'miembros'), 201);
    }

    public function show($id)
    {
        $comite = Comite::with('responsable', 'miembros', 'reuniones')->findOrFail($id);
        return response()->json($comite);
    }

    public function update(UpdateComiteRequest $request, $id)
    {
        $validated = $request->validated();

        $comite = Comite::findOrFail($id);
        $comite->update($validated);

        if (isset($validated['miembros'])) {
            $comite->miembros()->sync($validated['miembros']);
        }

        return response()->json($comite->load('responsable', 'miembros'));
    }

    public function destroy($id)
    {
        $comite = Comite::findOrFail($id);
        $comite->delete();
        return response()->json(['message' => 'Comité eliminado correctamente']);
    }
}
