<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrder extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'min:10'],
            'address' => ['required', 'string'],
            'delivery_time' => ['required', 'string'],
            'payment_id' => ['required', 'integer'],
            'comment' => ['string']
        ];
    }
}
