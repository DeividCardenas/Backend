<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndicadorValor;
use Illuminate\Http\Request;

class IndicadorValorController extends Controller
{
    public function index(Request $request)
    {
        $query = IndicadorValor::with('indicador', 'registradoPor');

        if ($request->has('id_indicador')) {
            $query->where('id_indicador', $request->id_indicador);
        }

        $valores = $query->orderBy('fecha', 'desc')->get();
        return response()->json($valores);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indicador' => 'required|exists:indicadores,id_indicador',
            'valor' => 'required|numeric',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $validated['registrado_por'] = $request->user()->id_usuario;

        $valor = IndicadorValor::create($validated);
        return response()->json($valor->load('indicador', 'registradoPor'), 201);
    }

    public function show($id)
    {
        $valor = IndicadorValor::with('indicador', 'registradoPor')->findOrFail($id);
        return response()->json($valor);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_indicador' => 'sometimes|required|exists:indicadores,id_indicador',
            'valor' => 'sometimes|required|numeric',
            'fecha' => 'sometimes|required|date',
            'observaciones' => 'nullable|string',
        ]);

        $valor = IndicadorValor::findOrFail($id);
        $valor->update($validated);
        return response()->json($valor->load('indicador', 'registradoPor'));
    }

    public function destroy($id)
    {
        $valor = IndicadorValor::findOrFail($id);
        $valor->delete();
        return response()->json(['message' => 'Valor eliminado correctamente']);
    }
}
