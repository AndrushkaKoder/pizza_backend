<?php

namespace App\Helpers;

class PhoneNumberHandler
{
    public function __construct(protected string $phoneNumber)
    {
    }

    public function normalizeFormat(): string
    {
        return preg_replace('/\D+/', '', $this->phoneNumber);
    }

}
