<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\InputDataHandlerTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUser;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    use InputDataHandlerTrait;

    /**
     * @return UserResource
     * Получить текущего авторизованного юзера
     */
    public function index(): UserResource
    {
        return (new UserResource(Auth::user()));
    }

    /**
     * @param UpdateUser $request
     * @return JsonResponse
     * Обновить юзера
     */
    public function update(UpdateUser $request): JsonResponse
    {
        $user = Auth::user();
        if ($phone = $request->input('phone')) {
            if (User::query()
                ->where('id', '!=', $user->id)
                ->wherePhone($this->normalizePhoneNumber($phone))->count()) {
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
            'phone' => $this->normalizePhoneNumber($request->input('phone')),
            'default_address' => $request->validated('address'),
            'email' => $request->validated('email') ?? $user->email,
            'password' => $request->validated('password') ?? $user->password
        ]);

        return response()->json([
            'success' => true,
            'message' => new UserResource($user)
        ]);
    }

    /**
     * @return JsonResponse
     * Удалить юзера
     */
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

    /**
     * @return JsonResponse
     * Разлогинить юзера
     */
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
