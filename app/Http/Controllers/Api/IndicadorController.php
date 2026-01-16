<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use Illuminate\Http\Request;
use App\Http\Requests\StoreIndicadorRequest;
use App\Http\Requests\UpdateIndicadorRequest;
use App\Services\IndicadorService;

class IndicadorController extends Controller
{
    protected $indicadorService;

    public function __construct(IndicadorService $indicadorService)
    {
        $this->indicadorService = $indicadorService;
    }

    public function index()
    {
        $indicadores = Indicador::with('responsable', 'valores')->where('activo', true)->paginate(15);
        return response()->json($indicadores);
    }

    public function store(StoreIndicadorRequest $request)
    {
        $indicador = $this->indicadorService->createIndicador($request->validated(), $request->user());
        return response()->json($indicador->load('responsable'), 201);
    }

    public function show($id)
    {
        $indicador = Indicador::with('responsable', 'valores.registradoPor')->findOrFail($id);
        return response()->json($indicador);
    }

    public function update(UpdateIndicadorRequest $request, $id)
    {
        $indicador = Indicador::findOrFail($id);
        $indicador = $this->indicadorService->updateIndicador($indicador, $request->validated(), $request->user());
        return response()->json($indicador->load('responsable'));
    }

    public function destroy($id)
    {
        $indicador = Indicador::findOrFail($id);
        $this->indicadorService->deactivateIndicador($indicador, request()->user());
        return response()->json(['message' => 'Indicador desactivado correctamente']);
    }
}
