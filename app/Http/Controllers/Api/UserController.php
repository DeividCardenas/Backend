<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $usuarios = User::with('roles')->where('activo', true)->paginate(15);
        return UserResource::collection($usuarios);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated(), $request->user());
        return (new UserResource($user->load('roles')))
            ->response()
            ->setStatusCode(201);
    }

    public function show($id)
    {
        $user = User::with('roles', 'comitesResponsable', 'comitesMiembro')->findOrFail($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user = $this->userService->updateUser($user, $request->validated(), $request->user());
        return new UserResource($user->load('roles'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->userService->deactivateUser($user, request()->user());
        return response()->json(['message' => 'Usuario desactivado correctamente']);
    }
}
