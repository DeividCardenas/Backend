<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use Illuminate\Http\Request;
use App\Http\Requests\StoreIndicadorRequest;
use App\Http\Requests\UpdateIndicadorRequest;

class IndicadorController extends Controller
{
    public function index()
    {
        $indicadores = Indicador::with('responsable', 'valores')->where('activo', true)->get();
        return response()->json($indicadores);
    }

    public function store(StoreIndicadorRequest $request)
    {
        $validated = $request->validated();
        $indicador = Indicador::create($validated);
        return response()->json($indicador->load('responsable'), 201);
    }

    public function show($id)
    {
        $indicador = Indicador::with('responsable', 'valores.registradoPor')->findOrFail($id);
        return response()->json($indicador);
    }

    public function update(UpdateIndicadorRequest $request, $id)
    {
        $validated = $request->validated();
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
