<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait HashPasswordTrait
{
    protected function cryptPassword(string $password): string
    {
        return Hash::make($password);
    }
}
