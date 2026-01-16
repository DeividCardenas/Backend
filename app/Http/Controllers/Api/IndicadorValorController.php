<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IndicadorValor;
use Illuminate\Http\Request;
use App\Http\Resources\IndicadorValorResource;
use App\Http\Requests\StoreIndicadorValorRequest;
use App\Http\Requests\UpdateIndicadorValorRequest;

class IndicadorValorController extends Controller
{
    public function index(Request $request)
    {
        $query = IndicadorValor::with('indicador', 'registradoPor');

        if ($request->has('id_indicador')) {
            $query->where('id_indicador', $request->id_indicador);
        }

        $valores = $query->orderBy('fecha', 'desc')->paginate(15);
        return IndicadorValorResource::collection($valores);
    }

    public function store(StoreIndicadorValorRequest $request)
    {
        $validated = $request->validated();

        $validated['registrado_por'] = $request->user()->id_usuario;

        $valor = IndicadorValor::create($validated);
        return (new IndicadorValorResource($valor->load('indicador', 'registradoPor')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $valor = IndicadorValor::with('indicador', 'registradoPor')->findOrFail($id);
        return new IndicadorValorResource($valor);
    }

    public function update(UpdateIndicadorValorRequest $request, $id)
    {
        $validated = $request->validated();

        $valor = IndicadorValor::findOrFail($id);
        $valor->update($validated);
        return new IndicadorValorResource($valor->load('indicador', 'registradoPor'));
    }

    public function destroy($id)
    {
        $valor = IndicadorValor::findOrFail($id);
        $valor->delete();
        return response()->json(['message' => 'Valor eliminado correctamente']);
    }
}
