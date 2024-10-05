<?php

namespace App\Http\Services\User;

use App\Helpers\Helper;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserService
{
    use Helper;

    #login exists user
    public function loginUser(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth');

        return response()->json([
            'success' => true,
            'message' => 'success auth',
            'token' => $token->plainTextToken
        ]);
    }

    #create new user
    public function registerNewUser(RegisterRequest $request): JsonResponse
    {
        if (User::query()
            ->where('phone', $this->normalizePhoneNumber($request->input('phone')))
            ->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Phone exists'
            ], 401);
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
            'message' => new UserResource($user)
        ], 201);
    }

    #get current auth user
    public function getAuthUser(): UserResource
    {
        return new UserResource(Auth::user());
    }

    #update exists user
    public function updateUser(UpdateUser $request, User $user): JsonResponse
    {
        if ($user->phone != $this->normalizePhoneNumber($request->input('phone'))) {
            if (User::query()->wherePhone($this->normalizePhoneNumber($request->input('phone')))->count()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Number exists'
                ]);
            }
        }
        $user->update([
            'name' => $request->validated('name'),
            'phone' => $this->normalizePhoneNumber($request->input('phone')) ?? $user->phone,
            'address' => $request->validated('name'),
            'email' => $request->validated('email') ?? $user->email,
            'password' => $request->validated('password') ?? $user->password
        ]);
        return response()->json([
            'success' => true,
            'message' => new UserResource($user)
        ]);
    }

    #delete exists user
    public function deleteUser(User $user): JsonResponse
    {
        Auth::user()->tokens()->delete();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'user was deleted'
        ]);
    }

    #logout exists user
    public function logoutUser(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'User logout'
        ]);
    }

}
