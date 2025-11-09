<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use Illuminate\Http\Request;

class ComiteController extends Controller
{
    public function index()
    {
        $comites = Comite::with('responsable', 'miembros')->get();
        return response()->json($comites);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'objetivo' => 'required|string',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
            'miembros' => 'array',
            'miembros.*' => 'exists:usuarios,id_usuario',
        ]);

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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'objetivo' => 'sometimes|required|string',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
            'miembros' => 'array',
            'miembros.*' => 'exists:usuarios,id_usuario',
        ]);

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
