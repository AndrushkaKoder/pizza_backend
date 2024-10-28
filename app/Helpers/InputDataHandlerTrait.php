<?php

namespace App\Helpers;

trait InputDataHandlerTrait
{
    public function normalizePhoneNumber(string $number): string
    {
        return preg_replace('/\D+/', '', $number);
    }

}
