<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\DTO\LoginDTO;
use App\Modules\User\DTO\RegisterUserDTO;
use App\Modules\User\Requests\LoginRequest;
use App\Modules\User\Requests\RegisterRequest;
use App\Modules\User\Resources\UserResource;
use App\Modules\User\UseCases\GetMe;
use App\Modules\User\UseCases\LoginUser;
use App\Modules\User\UseCases\Logout;
use App\Modules\User\UseCases\RefreshToken;
use App\Modules\User\UseCases\RegisterUser;
use App\Shared\Http\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class AuthController
{
    /**
     * @OA\Post(
     *   path="/auth/register",
     *   tags={"Auth"},
     *   summary="Register user",
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       required={"name","email","password"},
     *
     *       @OA\Property(property="name", type="string", maxLength=255, example="Admin"),
     *       @OA\Property(property="email", type="string", format="email", maxLength=255, example="admin@example.com"),
     *       @OA\Property(property="password", type="string", example="password")
     *     )
     *   ),
     *
     *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/AuthResponse")),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function register(RegisterRequest $request, RegisterUser $useCase): JsonResponse
    {
        $result = $useCase->handle(RegisterUserDTO::fromArray($request->validated()));

        return ApiResponse::data([
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
            'expires_in' => $result['expires_in'],
            'user' => new UserResource($result['user']),
        ], 201);
    }

    /**
     * @OA\Post(
     *   path="/auth/login",
     *   tags={"Auth"},
     *   summary="Login",
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       required={"email","password"},
     *
     *       @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *       @OA\Property(property="password", type="string", example="password")
     *     )
     *   ),
     *
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/AuthResponse")),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function login(LoginRequest $request, LoginUser $useCase): JsonResponse
    {
        $result = $useCase->handle(LoginDTO::fromArray($request->validated()));

        return ApiResponse::data([
            'access_token' => $result['access_token'],
            'token_type' => $result['token_type'],
            'expires_in' => $result['expires_in'],
            'user' => new UserResource($result['user']),
        ]);
    }

    /**
     * @OA\Get(
     *   path="/auth/me",
     *   tags={"Auth"},
     *   summary="Authenticated user data",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="user", ref="#/components/schemas/User")
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function me(GetMe $useCase): JsonResponse
    {
        return ApiResponse::data([
            'user' => new UserResource($useCase->handle()),
        ]);
    }

    /**
     * @OA\Post(
     *   path="/auth/refresh",
     *   tags={"Auth"},
     *   summary="Refresh JWT token",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="access_token", type="string"),
     *       @OA\Property(property="token_type", type="string", example="bearer"),
     *       @OA\Property(property="expires_in", type="integer", example=3600)
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function refresh(RefreshToken $useCase): JsonResponse
    {
        return ApiResponse::data($useCase->handle());
    }

    /**
     * @OA\Post(
     *   path="/auth/logout",
     *   tags={"Auth"},
     *   summary="Logout",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Logout $useCase): JsonResponse
    {
        $useCase->handle();

        return ApiResponse::message('Logged out.');
    }
}
