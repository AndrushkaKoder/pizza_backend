<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\InputDataHandlerTrait;
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

    use InputDataHandlerTrait;

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        if ($phone = $request->input('phone')) {
            if (User::query()->wherePhone($this->normalizePhoneNumber($phone))->count()) {
                return response()->json([
                    'message' => 'Number already exists',
                    'errors' => [
                        'phone' => ['Number already exists']
                    ]
                ]);
            }
        }

        $user = new User([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $this->normalizePhoneNumber($request->validated('phone')),
            'password' => $request->validated('password')
        ]);
        $user->save();

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => new UserResource($user),
            'token' => $user->createToken('auth')->plainTextToken
        ], 201);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
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
            'message' => 'success',
            'token' => $user->createToken('auth')->plainTextToken
        ]);
    }
}
