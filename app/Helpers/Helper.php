<?php

namespace App\Helpers;

trait Helper
{
    public function normalizePhoneNumber(string $number): string
    {
        return preg_replace('/\D+/', '', $number);
    }

}
