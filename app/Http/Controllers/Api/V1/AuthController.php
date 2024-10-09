<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function __construct(private readonly UserService $userService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->userService->loginUser($request);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        return $this->userService->registerNewUser($request);
    }
}
