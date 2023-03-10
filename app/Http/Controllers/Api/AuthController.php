<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = App::make(AuthService::class);
    }

    public function login(LoginRequest $request): object
    {
        $credentials = $request->validated();

        $response = $this->authService->login($credentials);

        if (isset($response['error'])) return response()->json($response['error'], $response['status']);

        return response()->json($response, 200);
    }

    public function register(RegisterRequest $request): object
    {
        $credentials = $request->validated();

        $response = $this->authService->register($credentials);

        if (isset($response['error'])) return response()->json($response['error'], $response['status']);

        return response()->json($response, 201);
    }

    public function logout(): object
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true
        ]);
    }
}
