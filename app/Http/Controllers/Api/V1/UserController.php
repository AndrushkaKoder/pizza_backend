<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PhoneNumberHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function index(): UserResource
    {
        return (new UserResource(Auth::user()));
    }


    public function update(UpdateUser $request): JsonResponse
    {
        $user = Auth::user();

        /** @var User|Authenticatable $user */

        $userPhone = null;

        if ($inputPhone = $request->input('phone')) {
            $userPhone = (new PhoneNumberHandler($inputPhone))->normalizeFormat();
        }

        if ($userPhone) {
            if (User::query()
                ->where('id', '!=', $user->id)
                ->wherePhone($userPhone)->count()) {
                return response()->json([
                    'message' => 'Number already exists',
                    'errors' => [
                        'phone' => ['Number already exists']
                    ]
                ]);
            }
        }


        $user->update([
            'name' => $request->validated('name'),
            'phone' => $userPhone,
            'default_address' => $request->validated('address'),
            'email' => $request->validated('email') ?? $user->email,
            'password' => $request->validated('password') ?? $user->password
        ]);

        return response()->json([
            'success' => true,
            'message' => new UserResource($user)
        ]);
    }


    public function delete(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'user deleted'
        ]);
    }


    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'User logout'
        ]);
    }

}
