<?php

namespace App\Modules\NewsCategory\Controllers;

use App\Modules\NewsCategory\DTO\CreateNewsCategoryDTO;
use App\Modules\NewsCategory\DTO\UpdateNewsCategoryDTO;
use App\Modules\NewsCategory\Models\NewsCategory;
use App\Modules\NewsCategory\Requests\AdminIndexRequest;
use App\Modules\NewsCategory\Requests\StoreNewsCategoryRequest;
use App\Modules\NewsCategory\Requests\UpdateNewsCategoryRequest;
use App\Modules\NewsCategory\Resources\NewsCategoryResource;
use App\Modules\NewsCategory\UseCases\CreateNewsCategory;
use App\Modules\NewsCategory\UseCases\DeleteNewsCategory;
use App\Modules\NewsCategory\UseCases\GetAdminNewsCategory;
use App\Modules\NewsCategory\UseCases\ListAdminNewsCategories;
use App\Modules\NewsCategory\UseCases\UpdateNewsCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class NewsCategoryAdminController
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *   path="/admin/news-categories",
     *   tags={"News Categories"},
     *   summary="List categories (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="search", in="query", required=false, @OA\Schema(type="string", maxLength=100)),
     *   @OA\Parameter(name="sort", in="query", required=false, @OA\Schema(type="string", enum={"created_at","-created_at","name","-name","slug","-slug"})),
     *   @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer", minimum=1, maximum=100)),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/NewsCategory")),
     *       @OA\Property(property="links", type="object"),
     *       @OA\Property(property="meta", type="object")
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(
        AdminIndexRequest $request,
        ListAdminNewsCategories $useCase
    ): AnonymousResourceCollection {
        $this->authorize('viewAny', NewsCategory::class);

        $perPage = (int) ($request->validated()['per_page'] ?? 15);
        $items = $useCase->handle($request->validated(), $perPage);

        return NewsCategoryResource::collection($items);
    }

    /**
     * @OA\Post(
     *   path="/admin/news-categories",
     *   tags={"News Categories"},
     *   summary="Create category (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       required={"name"},
     *
     *       @OA\Property(property="name", type="string", maxLength=255, example="Tech"),
     *       @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="tech")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Created",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/NewsCategory"))
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function store(
        StoreNewsCategoryRequest $request,
        CreateNewsCategory $useCase
    ): JsonResponse {
        $this->authorize('create', NewsCategory::class);

        $category = $useCase->handle(CreateNewsCategoryDTO::fromArray($request->validated()));

        return (new NewsCategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *   path="/admin/news-categories/{id}",
     *   tags={"News Categories"},
     *   summary="Get category (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/NewsCategory"))
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(int $id, GetAdminNewsCategory $useCase): JsonResponse
    {
        $category = $useCase->handle($id);
        $this->authorize('view', $category);

        return (new NewsCategoryResource($category))->response();
    }

    /**
     * @OA\Put(
     *   path="/admin/news-categories/{id}",
     *   tags={"News Categories"},
     *   summary="Update category (admin) - PUT",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="name", type="string", maxLength=255, example="Tech"),
     *       @OA\Property(property="slug", type="string", nullable=true, maxLength=255, example="tech")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/NewsCategory"))
     *   ),
     *
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Not found"),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     *
     * @OA\Patch(
     *   path="/admin/news-categories/{id}",
     *   tags={"News Categories"},
     *   summary="Update category (admin) - PATCH",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\RequestBody(required=true, @OA\JsonContent()),
     *
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *
     *     @OA\JsonContent(type="object", @OA\Property(property="data", ref="#/components/schemas/NewsCategory"))
     *   )
     * )
     */
    public function update(
        int $id,
        UpdateNewsCategoryRequest $request,
        GetAdminNewsCategory $getUseCase,
        UpdateNewsCategory $updateUseCase
    ): JsonResponse {
        $category = $getUseCase->handle($id);
        $this->authorize('update', $category);

        $payload = array_merge(
            $category->only(['name', 'slug']),
            $request->validated()
        );

        $updated = $updateUseCase->handle($category, UpdateNewsCategoryDTO::fromArray($payload));

        return (new NewsCategoryResource($updated))->response();
    }

    /**
     * @OA\Delete(
     *   path="/admin/news-categories/{id}",
     *   tags={"News Categories"},
     *   summary="Delete category (admin)",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *
     *   @OA\Response(response=204, description="No Content"),
     *   @OA\Response(response=401, description="Unauthorized"),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(
        int $id,
        GetAdminNewsCategory $getUseCase,
        DeleteNewsCategory $deleteUseCase
    ): JsonResponse {
        $category = $getUseCase->handle($id);
        $this->authorize('delete', $category);

        $deleteUseCase->handle($category);

        return response()->json(status: 204);
    }
}
