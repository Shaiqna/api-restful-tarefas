<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function createUser(array $credentials): bool
    {
        $user = DB::connection('mysql')
                ->table('users')
                    ->insert($credentials);


        return $user;
    }
}
