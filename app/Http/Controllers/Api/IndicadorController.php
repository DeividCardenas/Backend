<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreIndicadorRequest;
use App\Http\Requests\UpdateIndicadorRequest;

class IndicadorController extends Controller
{
    public function index()
    {
        $indicadores = Indicador::with('responsable', 'valores')->where('activo', true)->paginate(15);
        return response()->json($indicadores);
    }

    public function store(StoreIndicadorRequest $request)
    {
        $validated = $request->validated();
        $indicador = Indicador::create($validated);

        Log::info('Indicador creado por ' . $request->user()->correo . ': ' . $indicador->nombre);

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

        Log::info('Indicador actualizado por ' . $request->user()->correo . ': ' . $indicador->nombre);

        return response()->json($indicador->load('responsable'));
    }

    public function destroy($id)
    {
        $indicador = Indicador::findOrFail($id);
        $indicador->update(['activo' => false]);

        Log::info('Indicador desactivado por ' . request()->user()->correo . ': ' . $indicador->nombre);

        return response()->json(['message' => 'Indicador desactivado correctamente']);
    }
}
