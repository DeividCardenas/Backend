<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reunion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreReunionRequest;
use App\Http\Requests\UpdateReunionRequest;

class ReunionController extends Controller
{
    public function index(Request $request)
    {
        $query = Reunion::with('comite');

        if ($request->has('id_comite')) {
            $query->where('id_comite', $request->id_comite);
        }

        $reuniones = $query->orderBy('fecha', 'desc')->paginate(15);
        return response()->json($reuniones);
    }

    public function store(StoreReunionRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('archivo_acta')) {
            $validated['archivo_acta'] = $request->file('archivo_acta')->store('actas', 'public');
        }

        $reunion = Reunion::create($validated);
        return response()->json($reunion->load('comite'), 201);
    }

    public function show($id)
    {
        $reunion = Reunion::with('comite')->findOrFail($id);
        return response()->json($reunion);
    }

    public function update(UpdateReunionRequest $request, $id)
    {
        $validated = $request->validated();

        $reunion = Reunion::findOrFail($id);

        if ($request->hasFile('archivo_acta')) {
            if ($reunion->archivo_acta) {
                Storage::disk('public')->delete($reunion->archivo_acta);
            }
            $validated['archivo_acta'] = $request->file('archivo_acta')->store('actas', 'public');
        }

        $reunion->update($validated);
        return response()->json($reunion->load('comite'));
    }

    public function destroy($id)
    {
        $reunion = Reunion::findOrFail($id);

        if ($reunion->archivo_acta) {
            Storage::disk('public')->delete($reunion->archivo_acta);
        }

        $reunion->delete();
        return response()->json(['message' => 'Reunión eliminada correctamente']);
    }
}
