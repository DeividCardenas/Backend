<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use App\Http\Resources\ComiteResource;
use App\Http\Requests\StoreComiteRequest;
use App\Http\Requests\UpdateComiteRequest;
use App\Services\ComiteService;

class ComiteController extends Controller
{
    protected $comiteService;

    public function __construct(ComiteService $comiteService)
    {
        $this->comiteService = $comiteService;
    }

    public function index()
    {
        $comites = Comite::with('responsable', 'miembros')->paginate(15);
        return ComiteResource::collection($comites);
    }

    public function store(StoreComiteRequest $request)
    {
        $comite = $this->comiteService->createComite($request->validated(), $request->user());
        return (new ComiteResource($comite->load('responsable', 'miembros')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $comite = Comite::with('responsable', 'miembros', 'reuniones')->findOrFail($id);
        return new ComiteResource($comite);
    }

    public function update(UpdateComiteRequest $request, $id)
    {
        $comite = Comite::findOrFail($id);
        $comite = $this->comiteService->updateComite($comite, $request->validated(), $request->user());
        return new ComiteResource($comite->load('responsable', 'miembros'));
    }

    public function destroy($id)
    {
        $comite = Comite::findOrFail($id);
        $this->comiteService->deleteComite($comite, request()->user());
        return response()->json(['message' => 'Comité eliminado correctamente']);
    }
}
