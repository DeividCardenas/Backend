<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): AnonymousResourceCollection
    {
        $usuarios = User::with('roles')->where('activo', true)->paginate(15);
        return UserResource::collection($usuarios);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $dto = CreateUserDTO::fromArray($request->validated());
        $user = $this->userService->createUser($dto, $request->user());
        return (new UserResource($user->load('roles')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id): UserResource
    {
        $user = User::with('roles', 'comitesResponsable', 'comitesMiembro')->findOrFail($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id): UserResource
    {
        $user = User::findOrFail($id);
        $dto = UpdateUserDTO::fromArray($request->validated());
        $user = $this->userService->updateUser($user, $dto, $request->user());
        return new UserResource($user->load('roles'));
    }

    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);
        $this->userService->deactivateUser($user, request()->user());
        return response()->json(['message' => 'Usuario desactivado correctamente']);
    }
}
