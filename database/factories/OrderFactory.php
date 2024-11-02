<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{

    public function definition()
    {
        return [
            'user_id' => 1,
            'status_id' => 1,
            'payment_id' => 1,
            'delivery_time' => $this->faker->time('Y-m-d'),
            'total_sum' => $this->faker->numerify,
            'address' => $this->faker->address,
            'phone'=> $this->faker->phoneNumber,
        ];
    }
}
