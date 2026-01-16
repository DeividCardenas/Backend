<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Indicador;
use Illuminate\Http\Request;
use App\Http\Resources\IndicadorResource;
use App\Http\Requests\StoreIndicadorRequest;
use App\Http\Requests\UpdateIndicadorRequest;
use App\Services\IndicadorService;
use App\DTOs\CreateIndicadorDTO;
use App\DTOs\UpdateIndicadorDTO;

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
        return IndicadorResource::collection($indicadores);
    }

    public function store(StoreIndicadorRequest $request)
    {
        $dto = CreateIndicadorDTO::fromArray($request->validated());
        $indicador = $this->indicadorService->createIndicador($dto, $request->user());
        return (new IndicadorResource($indicador->load('responsable')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $indicador = Indicador::with('responsable', 'valores.registradoPor')->findOrFail($id);
        return new IndicadorResource($indicador);
    }

    public function update(UpdateIndicadorRequest $request, $id)
    {
        $indicador = Indicador::findOrFail($id);
        $dto = UpdateIndicadorDTO::fromArray($request->validated());
        $indicador = $this->indicadorService->updateIndicador($indicador, $dto, $request->user());
        return new IndicadorResource($indicador->load('responsable'));
    }

    public function destroy($id)
    {
        $indicador = Indicador::findOrFail($id);
        $this->indicadorService->deactivateIndicador($indicador, request()->user());
        return response()->json(['message' => 'Indicador desactivado correctamente']);
    }
}
