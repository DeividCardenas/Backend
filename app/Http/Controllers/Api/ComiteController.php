<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comite;
use App\Http\Resources\ComiteResource;
use App\Http\Requests\StoreComiteRequest;
use App\Http\Requests\UpdateComiteRequest;
use App\Services\ComiteService;
use App\DTOs\CreateComiteDTO;
use App\DTOs\UpdateComiteDTO;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class ComiteController extends Controller
{
    protected $comiteService;

    public function __construct(ComiteService $comiteService)
    {
        $this->comiteService = $comiteService;
    }

    public function index(): AnonymousResourceCollection
    {
        $comites = Comite::with('responsable', 'miembros')->paginate(15);
        return ComiteResource::collection($comites);
    }

    public function store(StoreComiteRequest $request): JsonResponse
    {
        $dto = CreateComiteDTO::fromArray($request->validated());
        $comite = $this->comiteService->createComite($dto, $request->user());
        return (new ComiteResource($comite->load('responsable', 'miembros')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id): ComiteResource
    {
        $comite = Comite::with('responsable', 'miembros', 'reuniones')->findOrFail($id);
        return new ComiteResource($comite);
    }

    public function update(UpdateComiteRequest $request, $id): ComiteResource
    {
        $comite = Comite::findOrFail($id);
        $dto = UpdateComiteDTO::fromArray($request->validated());
        $comite = $this->comiteService->updateComite($comite, $dto, $request->user());
        return new ComiteResource($comite->load('responsable', 'miembros'));
    }

    public function destroy($id): JsonResponse
    {
        $comite = Comite::findOrFail($id);
        $this->comiteService->deleteComite($comite, request()->user());
        return response()->json(['message' => 'Comité eliminado correctamente']);
    }
}
