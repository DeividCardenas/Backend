<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreComiteRequest;
use App\Http\Requests\UpdateComiteRequest;

class ComiteController extends Controller
{
    public function index()
    {
        $comites = Comite::with('responsable', 'miembros')->paginate(15);
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

        Log::info('Comité creado por ' . $request->user()->correo . ': ' . $comite->nombre);

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

        Log::info('Comité actualizado por ' . $request->user()->correo . ': ' . $comite->nombre);

        return response()->json($comite->load('responsable', 'miembros'));
    }

    public function destroy($id)
    {
        $comite = Comite::findOrFail($id);
        $nombre = $comite->nombre;
        $comite->delete();

        Log::info('Comité eliminado por ' . request()->user()->correo . ': ' . $nombre);

        return response()->json(['message' => 'Comité eliminado correctamente']);
    }
}
