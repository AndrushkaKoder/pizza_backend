<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\User\UserResource;
use App\Http\Services\User\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function __construct(private readonly UserService $userService)
    {
    }

    public function index(): UserResource
    {
        return $this->userService->getAuthUser();
    }

    public function update(UpdateUser $request, User $user): JsonResponse
    {
        return $this->userService->updateUser($request, $user);
    }

    public function delete(User $user): JsonResponse
    {
        return $this->userService->deleteUser($user);
    }

    public function logout(): JsonResponse
    {
        return $this->userService->logoutUser();
    }

    public function basket(User $user)
    {

    }

    public function orders(User $user)
    {

    }
}
