<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="Laravel Boilerplate API",
 *   version="1.0.0",
 *   description="API documentation (OpenAPI/Swagger)."
 * )
 *
 * @OA\Server(url="/api", description="API base URL")
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 *   description="Provide the token in the format: Bearer {token}"
 * )
 *
 * @OA\Tag(name="Auth", description="Authentication (JWT)")
 * @OA\Tag(name="News", description="News")
 * @OA\Tag(name="News Categories", description="News categories")
 *
 * @OA\Schema(
 *   schema="MessageResponse",
 *   type="object",
 *
 *   @OA\Property(property="message", type="string", example="Logged out.")
 * )
 *
 * @OA\Schema(
 *   schema="User",
 *   type="object",
 *
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="Admin"),
 *   @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
 *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *   schema="AuthResponse",
 *   type="object",
 *
 *   @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
 *   @OA\Property(property="token_type", type="string", example="bearer"),
 *   @OA\Property(property="expires_in", type="integer", example=3600),
 *   @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 *
 * @OA\Schema(
 *   schema="ValidationError",
 *   type="object",
 *
 *   @OA\Property(property="message", type="string", example="The given data was invalid."),
 *   @OA\Property(
 *     property="errors",
 *     type="object",
 *     additionalProperties=@OA\Schema(
 *       type="array",
 *
 *       @OA\Items(type="string")
 *     )
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="NewsCategory",
 *   type="object",
 *
 *   @OA\Property(property="id", type="integer", example=10),
 *   @OA\Property(property="name", type="string", example="Tech"),
 *   @OA\Property(property="slug", type="string", example="tech"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Technology category"),
 *   @OA\Property(property="is_active", type="boolean", example=true),
 *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 *
 * @OA\Schema(
 *   schema="News",
 *   type="object",
 *
 *   @OA\Property(property="id", type="integer", example=100),
 *   @OA\Property(property="news_category_id", type="integer", example=10),
 *   @OA\Property(property="title", type="string", example="My news"),
 *   @OA\Property(property="slug", type="string", example="my-news"),
 *   @OA\Property(property="excerpt", type="string", nullable=true, example="Summary"),
 *   @OA\Property(property="content", type="string", example="Content..."),
 *   @OA\Property(property="cover_image_url", type="string", nullable=true, example="https://cdn.exemplo.com/capa.jpg"),
 *   @OA\Property(property="published_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="status", type="string", example="draft"),
 *   @OA\Property(property="is_featured", type="boolean", nullable=true, example=false),
 *   @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *   @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
 * )
 */
final class OpenApi {}
