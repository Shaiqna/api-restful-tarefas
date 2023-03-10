<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\App;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
*/

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = App::make(AuthService::class);
    }

    /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      tags={"Authentication"},
     *      summary="Realiza login",
     *      description="Realiza login do usuário com as credenciais informadas",
     *      security={{ "bearerAuth": {} }},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="Email do usuário",
     *                      example="usuario@exemplo.com"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="Senha do usuário",
     *                      example="123456"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Login realizado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="access_token",
     *                  type="string",
     *                  description="Token de acesso do usuário"
     *              ),
     *              @OA\Property(
     *                  property="token_type",
     *                  type="string",
     *                  description="Tipo do token"
     *              ),
     *              @OA\Property(
     *                  property="expires_in",
     *                  type="integer",
     *                  description="Tempo de expiração do token em segundos"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Credenciais inválidas",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="Mensagem de erro"
     *              )
     *          )
     *      )
     * )
    */

    public function login(LoginRequest $request): object
    {
        $credentials = $request->validated();

        $response = $this->authService->login($credentials);

        if (isset($response['error'])) return response()->json($response['error'], $response['status']);

        return response()->json($response, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      tags={"Authentication"},
     *      summary="Cria novo usuário",
     *      description="Cria novo usuário com as informações informadas",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="Nome do usuário",
     *                      example="Fulano"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="Email do usuário",
     *                      example="fulano@example.com"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="Senha do usuário",
     *                      example="123456"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Usuário criado com sucesso",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/User"
     *              ),
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean",
     *                  example=true
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Erro de validação",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="error",
     *                  type="string",
     *                  description="Mensagem de erro"
     *              ),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  description="Lista de erros de validação",
     *                  @OA\AdditionalProperties(
     *                      type="array",
     *                      @OA\Items(
     *                          type="string"
     *                      )
     *                  )
     *              )
     *          )
     *      )
     * )
    */

    public function register(RegisterRequest $request): object
    {
        $credentials = $request->validated();

        $response = $this->authService->register($credentials);

        if (isset($response['error'])) return response()->json($response['error'], $response['status']);

        return response()->json($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Desloga usuário",
     *     tags={"Authentication"},
     *     security={ {"bearerAuth":{}} },
     *     @OA\Response(
     *         response="200",
     *         description="Usuário deslogado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Deslogado com sucesso"
     *             ),
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             )
     *         )
     *     )
     * )
    */

    public function logout(): object
    {
        $this->authService->logout();

        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true
        ]);
    }
}
