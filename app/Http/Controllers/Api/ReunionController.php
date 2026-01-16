<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reunion;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReunionRequest;
use App\Http\Requests\UpdateReunionRequest;
use App\Services\ReunionService;

class ReunionController extends Controller
{
    protected $reunionService;

    public function __construct(ReunionService $reunionService)
    {
        $this->reunionService = $reunionService;
    }

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
        $reunion = $this->reunionService->createReunion(
            $request->validated(),
            $request->file('archivo_acta')
        );
        return response()->json($reunion->load('comite'), 201);
    }

    public function show($id)
    {
        $reunion = Reunion::with('comite')->findOrFail($id);
        return response()->json($reunion);
    }

    public function update(UpdateReunionRequest $request, $id)
    {
        $reunion = Reunion::findOrFail($id);
        $reunion = $this->reunionService->updateReunion(
            $reunion,
            $request->validated(),
            $request->file('archivo_acta')
        );
        return response()->json($reunion->load('comite'));
    }

    public function destroy($id)
    {
        $reunion = Reunion::findOrFail($id);
        $this->reunionService->deleteReunion($reunion);
        return response()->json(['message' => 'Reunión eliminada correctamente']);
    }
}
