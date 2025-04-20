<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Register a new user",
     *     description="Registers a new user with the provided credentials",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterUserRequestSchema")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/UserResource")
     *             ),
     *             @OA\Property(property="errors", type="object", nullable=true),
     *         ),
     *     )
     * )
     */

    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        return ApiResponse::send(['user' => $user], 'User registered successfully', 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="User Login",
     *     description="Authenticates a user and returns a JWT token if credentials are valid.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful.",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="JWT token", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1Q...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return ApiResponse::send(null, 'Invalid credentials', Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return ApiResponse::send(null, 'Could not create token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::send(['token' => $token], 'Login successful');
    }
}
