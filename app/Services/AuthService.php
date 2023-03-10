<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Traits\HashPasswordTrait;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private UserRepository $userRepository;

    use HashPasswordTrait;

    public function __construct()
    {
        $this->userRepository = App::make(UserRepository::class);
    }

    public function login(array $credentials): array
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) return [['error' => 'Credenciais inválidas'], 'status' => 401];
        } catch (JWTException $e) {
            return [
                ['error' => 'Não foi possível criar o token'],
                'status' => 500
            ];
        }

        return ['token' => $token];
    }

    public function register(array $credentials): array
    {
        $credentials['password'] = $this->cryptPassword($credentials['password']);

        $user = $this->userRepository->createUser($credentials);

        if (!$user) return [['error' => 'Não foi possível criar o usuário'], 'status' => 500];

        return [
            'message' => 'Usuário criado com sucesso',
            'success' => true,
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
