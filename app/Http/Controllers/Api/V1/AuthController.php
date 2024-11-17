<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PhoneNumberHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $userPhone = (new PhoneNumberHandler($validatedData['phone']))->normalizeFormat();

        if (User::query()->wherePhone($userPhone)->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Number already exists',
                'errors' => [
                    'phone' => ['Number already exists']
                ]
            ], 400);
        }

        $user = new User([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $userPhone,
            'password' => $validatedData['password']
        ]);
        $user->save();

        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
            'token' => $user->createToken('auth')->plainTextToken
        ], 201);
    }


    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        /** @var User|Authenticatable $user */

        if (!$user->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'User was banned'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'success authenticate',
            'token' => $user->createToken('auth')->plainTextToken
        ]);
    }
}
