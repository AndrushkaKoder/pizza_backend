<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUser extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'address' => ['sometimes', 'string'],
            'email' => ['required', 'email', 'exclude_if:email,'. Auth::user()->email, 'unique:users'],
            'password' => ['nullable', 'confirmed', 'min:5'],
        ];
    }
}
