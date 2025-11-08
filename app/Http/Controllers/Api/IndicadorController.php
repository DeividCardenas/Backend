<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{
    public function index()
    {
        $indicadores = Indicador::with('responsable', 'valores')->where('activo', true)->get();
        return response()->json($indicadores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'formula' => 'nullable|string',
            'meta' => 'nullable|string',
            'unidad' => 'nullable|string|max:50',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
            'id_norma' => 'nullable|integer',
            'activo' => 'boolean',
        ]);

        $indicador = Indicador::create($validated);
        return response()->json($indicador->load('responsable'), 201);
    }

    public function show($id)
    {
        $indicador = Indicador::with('responsable', 'valores.registradoPor')->findOrFail($id);
        return response()->json($indicador);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'formula' => 'nullable|string',
            'meta' => 'nullable|string',
            'unidad' => 'nullable|string|max:50',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
            'id_norma' => 'nullable|integer',
            'activo' => 'boolean',
        ]);

        $indicador = Indicador::findOrFail($id);
        $indicador->update($validated);
        return response()->json($indicador->load('responsable'));
    }

    public function destroy($id)
    {
        $indicador = Indicador::findOrFail($id);
        $indicador->update(['activo' => false]);
        return response()->json(['message' => 'Indicador desactivado correctamente']);
    }
}
